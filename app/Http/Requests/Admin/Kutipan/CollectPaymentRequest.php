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
            'bilangan_tahun' => ['required', 'integer', 'min:1', 'max:5'],
            'tahun_mula' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_tamat' => ['required', 'integer', 'min:2000', 'max:2100', 'gte:tahun_mula'],
            'no_resit_transfer' => ['required', 'string', 'max:50'],
            'bukti_bayaran' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $tahunBayar = $this->input('tahun_bayar');
            $bilanganTahun = $this->input('bilangan_tahun');
            $tahunMula = $this->input('tahun_mula');
            $tahunTamat = $this->input('tahun_tamat');

            if ($tahunBayar === null || $bilanganTahun === null || $tahunMula === null || $tahunTamat === null) {
                return;
            }

            $tahunBayarInt = (int) $tahunBayar;
            $bilanganTahunInt = (int) $bilanganTahun;
            $tahunMulaInt = (int) $tahunMula;
            $tahunTamatInt = (int) $tahunTamat;

            $expectedTamat = $tahunBayarInt + ($bilanganTahunInt - 1);

            if ($tahunTamatInt !== $expectedTamat) {
                $validator->errors()->add('tahun_tamat', 'Tahun tamat tidak sepadan dengan bilangan tahun yang dipilih.');
            }

            if ($tahunMulaInt !== $tahunBayarInt) {
                $validator->errors()->add('tahun_mula', 'Tahun mula mesti sama dengan tahun bayar.');
            }
        });
    }
}
