<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\SecureFileUpload;
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
            'bukti_bayaran' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'mimetypes:image/jpeg,image/png,application/pdf',
                'max:5120',
                new SecureFileUpload,
            ],
        ];
    }
}
