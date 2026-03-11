<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateJabatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        /** @var \App\Models\Jabatan|null $jabatan */
        $jabatan = $this->route('jabatan');

        return [
            'nama_jabatan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jabatans', 'nama_jabatan')->ignore($jabatan?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan ini sudah wujud.',
        ];
    }
}

