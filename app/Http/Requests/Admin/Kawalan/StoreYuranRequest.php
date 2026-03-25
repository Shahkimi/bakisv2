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
            'jumlah' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'jenis_yuran.required' => 'Jenis yuran wajib diisi.',
            'jenis_yuran.unique' => 'Jenis yuran ini sudah wujud.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah mesti nombor.',
            'jumlah.min' => 'Jumlah mesti sekurang-kurangnya 0.',
        ];
    }
}
