<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class UpdateCommitteeMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'jawatan' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', File::image()->max(2048)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'jawatan.required' => 'Jawatan wajib diisi.',
            'photo.image' => 'Fail mesti imej (JPEG, PNG, JPG, GIF atau WEBP).',
            'photo.max' => 'Saiz imej tidak boleh melebihi 2MB.',
        ];
    }
}
