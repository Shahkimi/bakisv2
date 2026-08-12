<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use App\Services\MailSettingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class StoreMailSettingRequest extends FormRequest
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
            'host' => ['nullable', 'string', 'max:255'],
            'port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'scheme' => ['nullable', 'string', 'in:auto,smtps'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'from_address' => ['nullable', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->boolean('is_enabled')) {
                return;
            }

            $service = app(MailSettingService::class);

            // When enabling, host/port/from address must exist either from this submission or
            // already stored — ignoring Laravel's own config placeholder defaults (127.0.0.1,
            // hello@example.com), which are not real relay configuration.
            $hasHost = $this->filled('host') || $service->hasStoredHost();
            $hasPort = $this->filled('port') || $service->hasStoredPort();
            $hasFromAddress = $this->filled('from_address') || $service->hasStoredFromAddress();

            if (! $hasHost) {
                $validator->errors()->add('host', 'Pelayan (SMTP host) wajib diisi untuk mengaktifkan e-mel.');
            }

            if (! $hasPort) {
                $validator->errors()->add('port', 'Port wajib diisi untuk mengaktifkan e-mel.');
            }

            if (! $hasFromAddress) {
                $validator->errors()->add('from_address', 'Alamat pengirim wajib diisi untuk mengaktifkan e-mel.');
            }
        });
    }
}
