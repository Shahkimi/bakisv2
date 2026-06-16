<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use App\Services\TurnstileSettingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class StoreTurnstileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_enabled' => $this->boolean('is_enabled'),
        ]);
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'is_enabled' => ['required', 'boolean'],
            'site_key' => ['nullable', 'string', 'max:255'],
            'secret_key' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->boolean('is_enabled')) {
                return;
            }

            $service = app(TurnstileSettingService::class);

            // When enabling, both keys must exist either from this submission or already stored.
            $effectiveSiteKey = $this->filled('site_key') ? (string) $this->input('site_key') : $service->siteKey();
            $effectiveSecretKey = $this->filled('secret_key') ? (string) $this->input('secret_key') : $service->secretKey();

            if ($effectiveSiteKey === '') {
                $validator->errors()->add('site_key', 'Kunci tapak (site key) wajib diisi untuk mengaktifkan Turnstile.');
            }

            if ($effectiveSecretKey === '') {
                $validator->errors()->add('secret_key', 'Kunci rahsia (secret key) wajib diisi untuk mengaktifkan Turnstile.');
            }
        });
    }
}
