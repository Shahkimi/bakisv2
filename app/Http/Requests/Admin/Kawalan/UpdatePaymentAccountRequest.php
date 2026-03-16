<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class UpdatePaymentAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'qr_image' => ['nullable', File::image(allowSvg: true)->max(2048)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'account_name.required' => 'Nama akaun wajib diisi.',
            'account_number.required' => 'No. akaun wajib diisi.',
            'qr_image.image' => 'Fail mesti imej (JPEG, PNG, JPG atau SVG).',
            'qr_image.max' => 'Saiz imej tidak boleh melebihi 2MB.',
        ];
    }
}
