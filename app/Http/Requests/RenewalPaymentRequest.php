<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Member;
use App\Models\Payment;
use App\Rules\SecureFileUpload;
use App\Services\KutipanService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RenewalPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $maxSelectableYear = (int) now()->year + KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD;
        $allowedYears = range(2020, $maxSelectableYear);

        return [
            'no_kp' => ['required', 'digits:12', 'exists:members,no_kp'],
            'years' => ['required', 'array', 'min:1', 'max:10'],
            'years.*' => ['required', 'integer', 'distinct', Rule::in($allowedYears)],
            'bukti_bayaran' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'mimetypes:image/jpeg,image/png,application/pdf',
                'max:5120',
                new SecureFileUpload,
            ],
            'cf-turnstile-response' => \App\Rules\VerifyTurnstileToken::rules(),
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'cf-turnstile-response.required' => 'Sila lengkapkan pengesahan keselamatan.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $noKp = $this->input('no_kp');
            $years = $this->input('years', []);

            if (! is_string($noKp) || ! is_array($years)) {
                return;
            }

            $noKpNorm = preg_replace('/\D/', '', $noKp) ?? '';
            $member = Member::query()->where('no_kp', $noKpNorm)->first();
            if ($member === null) {
                return;
            }

            foreach ($years as $year) {
                if (! is_numeric($year)) {
                    continue;
                }

                $yearInt = (int) $year;

                foreach ([Payment::STATUS_APPROVED, Payment::STATUS_PENDING] as $status) {
                    $exists = Payment::query()
                        ->where('member_id', $member->id)
                        ->where('status', $status)
                        ->where('tahun_mula', '<=', $yearInt)
                        ->where(function ($q) use ($yearInt): void {
                            $q->whereNull('tahun_tamat')
                                ->orWhere('tahun_tamat', '>=', $yearInt);
                        })
                        ->exists();

                    if ($exists) {
                        $label = $status === Payment::STATUS_APPROVED ? 'sudah dibayar (disahkan)' : 'sedang menunggu pengesahan';
                        $validator->errors()->add('years', "Tahun {$yearInt} {$label}.");

                        break 2;
                    }
                }
            }

            $renewalFloor = app(KutipanService::class)->getMinimumRenewalYearAfterPendaftaran($member);
            if ($renewalFloor === null) {
                return;
            }

            foreach ($years as $year) {
                if (! is_numeric($year)) {
                    continue;
                }

                $yearInt = (int) $year;
                if ($yearInt < $renewalFloor) {
                    $validator->errors()->add(
                        'years',
                        "Tahun pembaharuan mesti selepas tahun liputan Pendaftaran Keahlian (mulai {$renewalFloor})."
                    );

                    break;
                }
            }
        });
    }
}
