<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreUserInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                Rule::unique('user_invitations', 'email'),
            ],
            'role' => ['required', 'integer', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
            'mode' => ['required', Rule::in(['invite', 'direct'])],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'E-mel wajib diisi.',
            'email.email' => 'Format e-mel tidak sah.',
            'email.unique' => 'E-mel ini sudah digunakan atau jemputan sedang menunggu.',
            'role.required' => 'Peranan wajib dipilih.',
            'role.in' => 'Peranan tidak sah.',
            'mode.required' => 'Kaedah pendaftaran wajib dipilih.',
            'mode.in' => 'Kaedah pendaftaran tidak sah.',
        ];
    }
}
