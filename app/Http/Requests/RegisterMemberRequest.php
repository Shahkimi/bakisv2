<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'no_kp' => ['required', 'digits:12', 'unique:members,no_kp'],
            'jantina' => ['required', 'in:L,P'],
            'email' => ['nullable', 'email', 'max:255'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jawatan_id' => ['required', 'exists:jawatans,id'],
            'alamat1' => ['nullable', 'string', 'max:255'],
            'alamat2' => ['nullable', 'string', 'max:255'],
            'poskod' => ['nullable', 'string', 'max:10'],
            'bandar' => ['nullable', 'string', 'max:255'],
            'negeri' => ['nullable', 'string', 'max:255'],
            'no_tel' => ['nullable', 'string', 'max:20'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'bukti_bayaran' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'cf-turnstile-response' => \App\Rules\VerifyTurnstileToken::rules(),
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'no_kp.unique' => 'No. KP ini sudah berdaftar.',
            'no_kp.digits' => 'No. KP mesti tepat 12 digit.',
            'cf-turnstile-response.required' => 'Sila lengkapkan pengesahan keselamatan.',
        ];
    }
}
