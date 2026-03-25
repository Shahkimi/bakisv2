<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Kutipan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class CollectPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $currentYear = (int) now()->year;

        return [
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'yuran_id' => [
                'required',
                'integer',
                Rule::exists('yurans', 'id')
                    ->where(fn ($q) => $q->where('is_active', true)->where('jumlah', 10.00)),
            ],
            'tahun_bayar' => ['required', 'integer', 'in:'.$currentYear],
            'tahun_mula' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'tahun_tamat' => ['nullable', 'integer', 'min:2000', 'max:2100', 'gte:tahun_mula'],
            'no_resit_transfer' => ['required', 'string', 'max:50'],
            'bukti_bayaran' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $hasMula = $this->filled('tahun_mula');
            $hasTamat = $this->filled('tahun_tamat');

            if ($hasMula !== $hasTamat) {
                if (! $hasMula) {
                    $validator->errors()->add('tahun_mula', 'Tahun mula dan tahun tamat perlu diisi bersama.');
                }

                if (! $hasTamat) {
                    $validator->errors()->add('tahun_tamat', 'Tahun mula dan tahun tamat perlu diisi bersama.');
                }
            }
        });
    }
}
