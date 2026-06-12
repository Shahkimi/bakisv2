<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kawalan;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePerlembagaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'tajuk' => ['required', 'string', 'max:255'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'tajuk.required' => 'Tajuk dokumen wajib diisi.',
            'pdf.file' => 'Fail tidak sah.',
            'pdf.mimes' => 'Hanya fail PDF dibenarkan.',
            'pdf.max' => 'Saiz fail tidak boleh melebihi 10 MB.',
        ];
    }
}
