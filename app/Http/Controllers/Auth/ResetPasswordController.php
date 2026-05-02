<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PerformPasswordResetRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

final class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, string $token): View
    {
        $email = $request->query('email');
        if (! is_string($email) || $email === '') {
            abort(404);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(PerformPasswordResetRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $status = Password::reset(
            $credentials,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', 'Kata laluan berjaya ditetapkan semula. Sila log masuk.');
        }

        $errorMessage = match ($status) {
            Password::INVALID_TOKEN => 'Pautan tetapan semula tidak sah atau telah luput. Sila minta pautan baharu.',
            Password::INVALID_USER => 'Akaun tidak ditemui.',
            Password::RESET_THROTTLED => 'Terlalu banyak percubaan. Sila tunggu sebentar.',
            default => 'Tetapan semula kata laluan gagal. Sila cuba lagi.',
        };

        return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}
