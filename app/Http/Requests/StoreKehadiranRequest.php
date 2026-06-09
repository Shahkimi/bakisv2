<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKehadiranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the No. KP to digits only before validation (matches Member normalization).
     */
    protected function prepareForValidation(): void
    {
        $noKp = $this->input('no_kp');
        if (is_string($noKp)) {
            $this->merge(['no_kp' => preg_replace('/\D/', '', $noKp)]);
        }
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'no_kp' => ['required', 'string', 'digits:12'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'no_kp.required' => 'No. KP wajib diisi.',
            'no_kp.digits' => 'No. KP mesti tepat 12 digit.',
        ];
    }
}
