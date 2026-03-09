<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenewalPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'no_kp' => ['required', 'digits:12', 'exists:members,no_kp'],
            'no_resit_transfer' => ['required', 'string', 'max:50'],
            'bukti_bayaran' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ];
    }
}
