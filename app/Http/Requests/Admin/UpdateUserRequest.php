<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('no_kp')) {
            return;
        }

        $digits = preg_replace('/\D/', '', (string) $this->input('no_kp', ''));

        $this->merge([
            'no_kp' => $digits === '' ? null : $digits,
        ]);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', 'integer', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
            'no_kp' => [
                'nullable',
                'string',
                'size:12',
                Rule::unique('users', 'no_kp')->ignore($user->id),
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'E-mel wajib diisi.',
            'email.email' => 'Format e-mel tidak sah.',
            'email.unique' => 'E-mel ini sudah digunakan.',
            'role.required' => 'Peranan wajib dipilih.',
            'role.in' => 'Peranan tidak sah.',
            'no_kp.size' => 'No. KP mestilah tepat 12 digit.',
            'no_kp.unique' => 'No. KP ini sudah digunakan oleh pengguna lain.',
        ];
    }
}
