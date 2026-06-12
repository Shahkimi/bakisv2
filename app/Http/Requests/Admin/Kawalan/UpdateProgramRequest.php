<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'nama_program' => ['required', 'string', 'max:255'],
            'tarikh' => ['required', 'date'],
            'waktu_mula' => ['required', 'string', 'max:20'],
            'waktu_tamat' => ['required', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
            'kehadiran' => ['sometimes', 'boolean'],
            'lokasi' => ['required_if:kehadiran,1,true', 'nullable', 'string', 'max:255'],
            'expires_at' => ['required_if:kehadiran,1,true', 'nullable', 'date', 'after:now'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_program.required' => 'Nama program wajib diisi.',
            'tarikh.required' => 'Tarikh wajib diisi.',
            'tarikh.date' => 'Tarikh tidak sah.',
            'waktu_mula.required' => 'Waktu mula wajib diisi.',
            'waktu_tamat.required' => 'Waktu tamat wajib diisi.',
            'lokasi.required_if' => 'Lokasi wajib diisi apabila Kehadiran diaktifkan.',
            'expires_at.required_if' => 'Tarikh tamat pautan wajib diisi apabila Kehadiran diaktifkan.',
            'expires_at.after' => 'Tarikh tamat mesti selepas masa sekarang.',
        ];
    }
}
