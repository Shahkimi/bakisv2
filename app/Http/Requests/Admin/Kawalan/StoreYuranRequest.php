<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class StoreYuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'jenis_yuran' => ['required', 'string', 'max:255', 'unique:yurans,jenis_yuran'],
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                'unique:yurans,code',
            ],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'is_show' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'jenis_yuran.required' => 'Jenis yuran wajib diisi.',
            'jenis_yuran.unique' => 'Jenis yuran ini sudah wujud.',
            'code.required' => 'Kod yuran wajib diisi.',
            'code.regex' => 'Kod yuran mesti huruf kecil, nombor, atau garis bawah sahaja.',
            'code.unique' => 'Kod yuran ini sudah wujud.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah mesti nombor.',
            'jumlah.min' => 'Jumlah mesti sekurang-kurangnya 0.',
            'is_show.required' => 'Pilihan Show Main Page wajib dibuat.',
        ];
    }
}
