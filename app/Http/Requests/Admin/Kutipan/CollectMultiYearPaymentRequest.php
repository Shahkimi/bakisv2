<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kutipan;

use App\Models\Member;
use App\Models\Payment;
use App\Services\KutipanService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class CollectMultiYearPaymentRequest extends FormRequest
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
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'yuran_id' => [
                'required',
                'integer',
                Rule::exists('yurans', 'id')
                    ->where(fn ($q) => $q->where('is_active', true)->where('jumlah', 10.00)),
            ],
            'years' => ['required', 'array', 'min:1', 'max:10'],
            'years.*' => ['required', 'integer', 'distinct:years', Rule::in($allowedYears)],
            'no_resit_transfer' => ['required', 'string', 'max:50'],
            'bukti_bayaran' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $memberId = $this->input('member_id');
            $years = $this->input('years', []);

            if (! is_numeric($memberId) || ! is_array($years)) {
                return;
            }

            $memberIdInt = (int) $memberId;

            foreach ($years as $year) {
                if (! is_numeric($year)) {
                    continue;
                }

                $yearInt = (int) $year;

                $existingPayment = Payment::query()
                    ->where('member_id', $memberIdInt)
                    ->where('status', Payment::STATUS_APPROVED)
                    ->where(function ($q) use ($yearInt): void {
                        $q->where('tahun_mula', '<=', $yearInt)
                            ->where(function ($q2) use ($yearInt): void {
                                $q2->whereNull('tahun_tamat')
                                    ->orWhere('tahun_tamat', '>=', $yearInt);
                            });
                    })
                    ->exists();

                if ($existingPayment) {
                    $validator->errors()->add('years', "Tahun {$yearInt} sudah dibayar.");

                    break;
                }
            }

            $member = Member::query()->find($memberIdInt);
            if ($member === null) {
                return;
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
