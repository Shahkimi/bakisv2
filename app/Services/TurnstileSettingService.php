<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

final class TurnstileSettingService
{
    public const string ENABLED_KEY = 'turnstile.enabled';

    public const string SITE_KEY = 'turnstile.site_key';

    public const string SECRET_KEY = 'turnstile.secret_key';

    private const string CACHE_PREFIX = 'turnstile_setting';

    private const int CACHE_TTL = 3600;

    public function enabled(): bool
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::ENABLED_KEY,
            self::CACHE_TTL,
            function (): bool {
                $value = Setting::query()->where('key', self::ENABLED_KEY)->value('value');

                if ($value === null) {
                    return (bool) config('services.turnstile.enabled', false);
                }

                return $value === '1';
            }
        );
    }

    public function siteKey(): string
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::SITE_KEY,
            self::CACHE_TTL,
            function (): string {
                $value = Setting::query()->where('key', self::SITE_KEY)->value('value');

                return is_string($value) && $value !== ''
                    ? $value
                    : (string) config('services.turnstile.site_key', '');
            }
        );
    }

    public function secretKey(): string
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::SECRET_KEY,
            self::CACHE_TTL,
            function (): string {
                $value = Setting::query()->where('key', self::SECRET_KEY)->value('value');

                return is_string($value) && $value !== ''
                    ? $value
                    : (string) config('services.turnstile.secret_key', '');
            }
        );
    }

    public function hasKeys(): bool
    {
        return $this->siteKey() !== '' && $this->secretKey() !== '';
    }

    public function hasSecret(): bool
    {
        return $this->secretKey() !== '';
    }

    /**
     * Turnstile is only active (challenge rendered + verified) when the admin
     * has enabled it AND both keys are present.
     */
    public function isActive(): bool
    {
        return $this->enabled() && $this->hasKeys();
    }

    public function update(bool $enabled, ?string $siteKey, ?string $secretKey): void
    {
        $this->forget();

        Setting::query()->updateOrCreate(
            ['key' => self::ENABLED_KEY],
            ['value' => $enabled ? '1' : '0'],
        );

        // Only overwrite keys when a non-empty value is provided, so leaving a
        // field blank in the admin form keeps the existing stored value.
        if (is_string($siteKey) && $siteKey !== '') {
            Setting::query()->updateOrCreate(
                ['key' => self::SITE_KEY],
                ['value' => $siteKey],
            );
        }

        if (is_string($secretKey) && $secretKey !== '') {
            Setting::query()->updateOrCreate(
                ['key' => self::SECRET_KEY],
                ['value' => $secretKey],
            );
        }

        $this->forget();
    }

    private function forget(): void
    {
        Cache::forget(self::CACHE_PREFIX.'.'.self::ENABLED_KEY);
        Cache::forget(self::CACHE_PREFIX.'.'.self::SITE_KEY);
        Cache::forget(self::CACHE_PREFIX.'.'.self::SECRET_KEY);
    }
}
