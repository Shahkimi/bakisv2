<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

final class MailSettingService
{
    public const string ENABLED_KEY = 'mail.enabled';

    public const string HOST_KEY = 'mail.host';

    public const string PORT_KEY = 'mail.port';

    public const string SCHEME_KEY = 'mail.scheme';

    public const string USERNAME_KEY = 'mail.username';

    public const string PASSWORD_KEY = 'mail.password';

    public const string FROM_ADDRESS_KEY = 'mail.from_address';

    public const string FROM_NAME_KEY = 'mail.from_name';

    private const string CACHE_PREFIX = 'mail_setting';

    private const int CACHE_TTL = 3600;

    /**
     * One-shot bypass so a deliberate test send can go out while the master
     * switch is off, without weakening the kill-switch for every other mailer.
     */
    private bool $bypassDisabledForNextSend = false;

    public function enabled(): bool
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::ENABLED_KEY,
            self::CACHE_TTL,
            function (): bool {
                $value = Setting::query()->where('key', self::ENABLED_KEY)->value('value');

                // No stored preference yet: mail keeps working as before this feature existed.
                return $value === null ? true : $value === '1';
            }
        );
    }

    /**
     * Whether email is both switched on and actually deliverable somewhere a
     * recipient can read it. Combines the admin toggle with {@see isConfigured()}.
     */
    public function isOperational(): bool
    {
        return $this->enabled() && $this->isConfigured();
    }

    /**
     * Whether a real mail transport is in place, independent of the on/off
     * toggle. An admin-saved host always counts. Otherwise falls back to the
     * `.env` mailer: `log` is treated as unconfigured (a fresh install ships
     * `MAIL_MAILER=log`, which delivers nowhere a recipient can read), a
     * placeholder `smtp` host (Laravel's default `127.0.0.1`) is treated as
     * unconfigured, and any other deliberately chosen transport (`array` used
     * in tests, `ses`, `sendmail`, a real smtp host, etc.) counts as configured.
     *
     * Note: a local Mailpit-style setup using `MAIL_HOST=127.0.0.1` will read
     * as unconfigured — use `localhost` instead, or save the host on the
     * admin settings page.
     */
    public function isConfigured(): bool
    {
        if ($this->hasStoredHost()) {
            return true;
        }

        $mailer = (string) config('mail.default');

        if ($mailer === 'log') {
            return false;
        }

        if ($mailer === 'smtp') {
            $host = (string) config('mail.mailers.smtp.host', '');

            return $host !== '' && $host !== '127.0.0.1';
        }

        return true;
    }

    public function host(): string
    {
        return $this->stringSetting(self::HOST_KEY, (string) config('mail.mailers.smtp.host', ''));
    }

    public function port(): ?int
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::PORT_KEY,
            self::CACHE_TTL,
            function (): ?int {
                $stored = $this->rawStoredValue(self::PORT_KEY);
                if ($stored !== null) {
                    return (int) $stored;
                }

                $configured = config('mail.mailers.smtp.port');

                return $configured !== null ? (int) $configured : null;
            }
        );
    }

    /** "auto" (STARTTLS negotiated) or "smtps" (implicit TLS). */
    public function scheme(): string
    {
        return $this->stringSetting(self::SCHEME_KEY, 'auto');
    }

    public function username(): string
    {
        return $this->stringSetting(self::USERNAME_KEY, (string) config('mail.mailers.smtp.username', ''));
    }

    public function hasPassword(): bool
    {
        return $this->storedPassword() !== null || (string) config('mail.mailers.smtp.password', '') !== '';
    }

    public function fromAddress(): string
    {
        return $this->stringSetting(self::FROM_ADDRESS_KEY, (string) config('mail.from.address', ''));
    }

    public function fromName(): string
    {
        return $this->stringSetting(self::FROM_NAME_KEY, (string) config('mail.from.name', ''));
    }

    /**
     * Whether an admin has actually saved this field, ignoring Laravel's own
     * config-level placeholder defaults (e.g. `mail.mailers.smtp.host`
     * defaults to `127.0.0.1`, `mail.from.address` to `hello@example.com`).
     * Used to validate "required when enabling" without those placeholders
     * masquerading as real relay configuration.
     */
    public function hasStoredHost(): bool
    {
        return $this->rawStoredValue(self::HOST_KEY) !== null;
    }

    public function hasStoredPort(): bool
    {
        return $this->rawStoredValue(self::PORT_KEY) !== null;
    }

    public function hasStoredFromAddress(): bool
    {
        return $this->rawStoredValue(self::FROM_ADDRESS_KEY) !== null;
    }

    /**
     * @param  array{is_enabled: bool, host: ?string, port: ?int, scheme: ?string, username: ?string, password: ?string, from_address: ?string, from_name: ?string}  $data
     */
    public function update(array $data): void
    {
        $this->forget();

        Setting::query()->updateOrCreate(
            ['key' => self::ENABLED_KEY],
            ['value' => $data['is_enabled'] ? '1' : '0'],
        );

        $this->putIfProvided(self::HOST_KEY, $data['host'] ?? null);
        $this->putIfProvided(self::PORT_KEY, $data['port'] !== null ? (string) $data['port'] : null);
        $this->putIfProvided(self::SCHEME_KEY, $data['scheme'] ?? null);
        $this->putIfProvided(self::USERNAME_KEY, $data['username'] ?? null);
        $this->putIfProvided(self::FROM_ADDRESS_KEY, $data['from_address'] ?? null);
        $this->putIfProvided(self::FROM_NAME_KEY, $data['from_name'] ?? null);

        // Leaving the password field blank keeps the currently stored secret.
        if (is_string($data['password']) && $data['password'] !== '') {
            Setting::query()->updateOrCreate(
                ['key' => self::PASSWORD_KEY],
                ['value' => Crypt::encryptString($data['password'])],
            );
        }

        $this->forget();
    }

    /**
     * Override the runtime `mail` config from stored settings, so notifications
     * sent via the default `smtp` mailer use the admin-configured relay.
     *
     * Only activates once an admin has actually saved a host on the settings
     * page — otherwise `host()`'s config-fallback (Laravel defaults
     * `mail.mailers.smtp.host` to `127.0.0.1`) would always be non-empty and
     * this would force `mail.default` to `smtp` on every request, silently
     * overriding an intentional `.env` mailer choice (e.g. `log` or the
     * `array` driver used in tests).
     */
    public function applyRuntimeConfig(): void
    {
        if (! $this->hasStoredHost()) {
            return;
        }

        $host = $this->host();

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.scheme' => $this->scheme() === 'smtps' ? 'smtps' : null,
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $this->port() ?? 587,
            'mail.mailers.smtp.username' => $this->username() ?: null,
            'mail.mailers.smtp.password' => $this->storedPassword() ?? config('mail.mailers.smtp.password'),
        ]);

        $fromAddress = $this->fromAddress();
        if ($fromAddress !== '') {
            config(['mail.from.address' => $fromAddress]);
        }

        $fromName = $this->fromName();
        if ($fromName !== '') {
            config(['mail.from.name' => $fromName]);
        }
    }

    public function allowNextSendWhileDisabled(): void
    {
        $this->bypassDisabledForNextSend = true;
    }

    public function consumeSendBypass(): bool
    {
        $bypass = $this->bypassDisabledForNextSend;
        $this->bypassDisabledForNextSend = false;

        return $bypass;
    }

    private function storedPassword(): ?string
    {
        $value = $this->rawStoredValue(self::PASSWORD_KEY);

        return $value !== null ? Crypt::decryptString($value) : null;
    }

    private function rawStoredValue(string $key): ?string
    {
        $value = Setting::query()->where('key', $key)->value('value');

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function stringSetting(string $key, string $default): string
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.$key,
            self::CACHE_TTL,
            fn (): string => $this->rawStoredValue($key) ?? $default
        );
    }

    private function putIfProvided(string $key, ?string $value): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    private function forget(): void
    {
        foreach ([
            self::ENABLED_KEY,
            self::HOST_KEY,
            self::PORT_KEY,
            self::SCHEME_KEY,
            self::USERNAME_KEY,
            self::FROM_ADDRESS_KEY,
            self::FROM_NAME_KEY,
        ] as $key) {
            Cache::forget(self::CACHE_PREFIX.'.'.$key);
        }
    }
}
