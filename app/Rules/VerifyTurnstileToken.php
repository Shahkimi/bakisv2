<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\TurnstileSettingService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class VerifyTurnstileToken implements ValidationRule
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Validation rules for the Turnstile response field. Only enforces the
     * challenge when Turnstile is active (enabled by the admin + keys present),
     * otherwise the field is optional so local/test flows are unaffected.
     *
     * @return array<int, mixed>
     */
    public static function rules(): array
    {
        return app(TurnstileSettingService::class)->isActive()
            ? ['required', 'string', new self]
            : ['nullable'];
    }

    /**
     * @param  Closure(string):void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = app(TurnstileSettingService::class);

        // Skip verification entirely when Turnstile is not active.
        if (! $service->isActive()) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Sila lengkapkan pengesahan keselamatan.');

            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post(self::VERIFY_URL, [
                'secret' => $service->secretKey(),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification request failed.', [
                'exception' => $e->getMessage(),
            ]);
            $fail('Pengesahan keselamatan gagal. Sila cuba lagi.');

            return;
        }

        if (! $response->successful() || $response->json('success') !== true) {
            Log::warning('Turnstile verification rejected.', [
                'status' => $response->status(),
                'errors' => $response->json('error-codes'),
            ]);
            $fail('Pengesahan keselamatan gagal. Sila cuba lagi.');
        }
    }
}
