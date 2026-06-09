<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class StoreLogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'logo' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'logo.required' => 'Sila pilih fail logo.',
            'logo.file' => 'Fail tidak sah.',
            'logo.mimes' => 'Format dibenarkan: JPG, PNG, GIF.',
            'logo.max' => 'Saiz fail tidak boleh melebihi 2 MB.',
        ];
    }
}
