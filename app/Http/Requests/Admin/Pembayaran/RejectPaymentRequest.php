<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

final class RejectPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'catatan_admin' => ['nullable', 'string'],
        ];
    }
}

