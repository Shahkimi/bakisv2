<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Pembayaran;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;

final class WaivePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        /** @var Payment|null $payment */
        $payment = $this->route('payment');
        $isDirectWaive = ! ($payment?->hasPendingWaiverRequest() ?? false);

        return [
            'waiver_reason' => $isDirectWaive
                ? ['required', 'string', 'max:1000']
                : ['nullable', 'string', 'max:1000'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'waiver_reason.required' => 'Sila nyatakan sebab pembatalan.',
        ];
    }
}
