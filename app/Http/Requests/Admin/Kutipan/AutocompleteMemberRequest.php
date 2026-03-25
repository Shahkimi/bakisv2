<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kutipan;

use Illuminate\Foundation\Http\FormRequest;

final class AutocompleteMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'search' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
