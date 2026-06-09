<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAcaraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'nama_acara' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'waktu' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_acara.required' => 'Nama acara wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'waktu.required' => 'Waktu wajib diisi.',
            'expires_at.required' => 'Tarikh tamat pautan wajib diisi.',
        ];
    }
}
