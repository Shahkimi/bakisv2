<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordByKpRequest;
use App\Models\User;
use App\Services\MailSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

final class ForgotPasswordController extends Controller
{
    public function __construct(private readonly MailSettingService $mailSetting) {}

    /**
     * Send password reset link to the user's email when No. KP matches a stored account.
     * Always redirects with a generic notice (no KP enumeration).
     */
    public function sendResetLink(ForgotPasswordByKpRequest $request): RedirectResponse
    {
        if (! $this->mailSetting->isOperational()) {
            return redirect()->route('login')->withErrors([
                'mail' => 'Tetapan semula kata laluan melalui e-mel tidak tersedia buat masa ini kerana e-mel sistem dimatikan atau belum dikonfigurasi. Sila hubungi pentadbir sistem.',
            ]);
        }

        $kp = $request->validated()['no_kp'];

        $user = User::query()->where('no_kp', $kp)->first();

        if ($user !== null) {
            $status = Password::sendResetLink([
                'email' => $user->email,
            ]);

            /*
             * Broker throttle blocks a second email if a token was created recently for that e-mail.
             * Users often retry before receiving mail; clear the pending token once and resend so the
             * message is actually dispatched without weakening route-level rate limits.
             */
            if ($status === Password::RESET_THROTTLED) {
                Password::deleteToken($user);
                $status = Password::sendResetLink([
                    'email' => $user->email,
                ]);
            }

            if ($status !== Password::RESET_LINK_SENT) {
                Log::warning('Password reset email was not sent after KP lookup.', [
                    'status' => $status,
                    'user_id' => $user->id,
                ]);
            }
        }

        return redirect()
            ->route('login')
            ->with(
                'reset_link_notice',
                'Jika No. KP berdaftar dalam sistem, pautan tetapan semula kata laluan telah dihantar ke e-mel akaun anda. Semak folder spam. Jika tiada e-mel, tunggu sebentar dan cuba semula (had sistem & pembekal e-mel).'
            );
    }
}
