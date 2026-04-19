<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class InvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = UserInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('invitation.expired');
        }

        return view('invitation.accept', [
            'invitation' => $invitation,
        ]);
    }

    public function store(AcceptInvitationRequest $request, string $token): RedirectResponse
    {
        $invitation = UserInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'Jemputan telah tamat tempoh. Sila minta pentadbir menghantar semula.');
        }

        DB::transaction(function () use ($invitation, $request): void {
            User::create([
                'name' => $invitation->name,
                'email' => $invitation->email,
                'password' => $request->validated()['password'],
                'role' => $invitation->role,
                'email_verified_at' => now(),
            ]);

            $invitation->delete();
        });

        return redirect()->route('login')
            ->with('success', 'Akaun berjaya dicipta. Sila log masuk.');
    }
}
