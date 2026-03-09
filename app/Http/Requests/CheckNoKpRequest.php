<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckNoKpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
