<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class StoreFaviconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, string|File>>
     */
    public function rules(): array
    {
        return [
            'favicon' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,ico', 'max:512'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'favicon.required' => 'Sila pilih fail favicon.',
            'favicon.file' => 'Fail tidak sah.',
            'favicon.mimes' => 'Format dibenarkan: JPG, PNG, GIF, ICO.',
            'favicon.max' => 'Saiz fail tidak boleh melebihi 512 KB.',
        ];
    }
}
