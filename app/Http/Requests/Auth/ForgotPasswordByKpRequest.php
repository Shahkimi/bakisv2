<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ForgotPasswordByKpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $raw = $this->input('no_kp');
        if ($raw === null || $raw === '') {
            return;
        }

        $digits = preg_replace('/\D/', '', (string) $raw) ?? '';

        $this->merge([
            'no_kp' => $digits,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'no_kp' => ['required', 'string', 'size:12', 'regex:/^\d{12}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'no_kp.required' => 'No. KP wajib diisi.',
            'no_kp.size' => 'No. KP mesti tepat 12 digit.',
            'no_kp.regex' => 'No. KP hanya boleh mengandungi nombor (12 digit).',
        ];
    }
}
