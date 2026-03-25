<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use App\Models\Yuran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateYuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        /** @var Yuran|null $yuran */
        $yuran = $this->route('yuran');

        return [
            'jenis_yuran' => [
                'required',
                'string',
                'max:255',
                Rule::unique('yurans', 'jenis_yuran')->ignore($yuran?->id),
            ],
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
