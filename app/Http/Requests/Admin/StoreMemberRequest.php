<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $member = $this->route('member');
        $rules = [
            'no_ahli' => ['nullable', 'string', 'max:50', Rule::unique('members', 'no_ahli')->ignore($member?->id)],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jawatan_id' => ['required', 'exists:jawatans,id'],
            'member_status_id' => ['required', 'exists:member_statuses,id'],
            'nama' => ['required', 'string', 'max:255'],
            'no_kp' => ['required', 'digits:12', Rule::unique('members', 'no_kp')->ignore($member?->id)],
            'email' => ['nullable', 'email', 'max:255'],
            'jantina' => ['required', 'in:L,P'],
            'alamat1' => ['nullable', 'string', 'max:255'],
            'alamat2' => ['nullable', 'string', 'max:255'],
            'poskod' => ['nullable', 'string', 'max:10'],
            'bandar' => ['nullable', 'string', 'max:255'],
            'negeri' => ['nullable', 'string', 'max:255'],
            'no_tel' => ['nullable', 'string', 'max:20'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'catatan' => ['nullable', 'string'],
            'tarikh_daftar' => ['required', 'date'],
            'approve_immediately' => ['nullable', 'boolean'],
            'tahun_bayar' => [
                $this->boolean('approve_immediately') ? 'required' : 'nullable',
                'integer', 'min:2000', 'max:2100',
            ],
            'yuran_id' => [
                $this->boolean('approve_immediately') ? 'required' : 'nullable',
                'exists:yurans,id',
            ],
            'tahun_mula' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'tahun_tamat' => ['nullable', 'integer', 'min:2000', 'max:2100', 'gte:tahun_mula'],
            'no_resit_transfer' => [
                $this->boolean('approve_immediately') ? 'required' : 'nullable',
                'string', 'max:50',
            ],
            'no_resit_sistem' => ['nullable', 'string', 'max:50'],
            'bukti_bayaran' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ];

        return $rules;
    }
}
