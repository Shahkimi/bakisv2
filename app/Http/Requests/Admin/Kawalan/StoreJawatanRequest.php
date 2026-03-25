<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class StoreJawatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'kod_jawatan' => ['required', 'string', 'max:10', 'unique:jawatans,kod_jawatan'],
            'nama_jawatan' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'kod_jawatan.required' => 'Kod jawatan wajib diisi.',
            'kod_jawatan.unique' => 'Kod jawatan ini sudah wujud.',
            'nama_jawatan.required' => 'Nama jawatan wajib diisi.',
        ];
    }
}
