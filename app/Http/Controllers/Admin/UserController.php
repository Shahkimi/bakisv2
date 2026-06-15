<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserInvitationRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserCredentialsNotification;
use App\Notifications\UserInvitationNotification;
use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserManagementService $userManagementService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.pengguna');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->userManagementService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StoreUserInvitationRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['mode'] === 'direct') {
            return $this->storeDirectUser($data);
        }

        $invitation = DB::transaction(function () use ($data): UserInvitation {
            return UserInvitation::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'token' => Str::random(64),
                'role' => $data['role'],
                'expires_at' => now()->addHours(24),
            ]);
        });

        // Hantar e-mel selepas respons dikembalikan kepada pelayar supaya SweetAlert
        // muncul serta-merta tanpa menunggu sambungan SMTP. Tidak memerlukan queue worker.
        defer(function () use ($invitation): void {
            try {
                Notification::route('mail', [$invitation->email => $invitation->name])
                    ->notify(new UserInvitationNotification($invitation));
            } catch (\Throwable $e) {
                Log::error('Gagal menghantar e-mel jemputan pengguna.', [
                    'invitation_id' => $invitation->id,
                    'email' => $invitation->email,
                    'exception' => $e->getMessage(),
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Jemputan dicipta. E-mel sedang dihantar.',
        ]);
    }

    /**
     * Create the user immediately with an auto-generated temporary password.
     *
     * @param  array<string, mixed>  $data
     */
    private function storeDirectUser(array $data): JsonResponse
    {
        $temporaryPassword = Str::password(12);

        $user = DB::transaction(function () use ($data, $temporaryPassword): User {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => $temporaryPassword,
                'email_verified_at' => now(),
                'must_change_password' => true,
            ]);
        });

        try {
            Notification::route('mail', [$user->email => $user->name])
                ->notify(new UserCredentialsNotification($user, $temporaryPassword));
        } catch (\Throwable $e) {
            Log::error('Gagal menghantar e-mel kelayakan pengguna.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Akaun dicipta, tetapi e-mel gagal dihantar. Sila salin kata laluan sementara di bawah dan berikan kepada pengguna.',
                'temp_password' => $temporaryPassword,
                'email' => $user->email,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akaun dicipta dan kata laluan sementara telah dihantar ke e-mel.',
            'temp_password' => $temporaryPassword,
            'email' => $user->email,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        if ($user->id === auth()->id() && isset($request->validated()['role']) && (int) $request->validated()['role'] !== User::ROLE_ADMIN) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh menurunkan peranan akaun sendiri daripada Admin.',
            ], 422);
        }

        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pengguna telah dikemas kini.',
        ]);
    }

    public function resetPassword(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh menetapkan semula kata laluan akaun sendiri. Sila gunakan halaman profil.',
            ], 422);
        }

        $temporaryPassword = Str::password(12);

        $user->update([
            'password' => $temporaryPassword,
            'must_change_password' => true,
        ]);

        try {
            Notification::route('mail', [$user->email => $user->name])
                ->notify(new UserCredentialsNotification($user, $temporaryPassword, isReset: true));
        } catch (\Throwable $e) {
            Log::error('Gagal menghantar e-mel tetapan semula kata laluan pengguna.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kata laluan ditetapkan semula, tetapi e-mel gagal dihantar. Sila salin kata laluan sementara di bawah dan berikan kepada pengguna.',
                'temp_password' => $temporaryPassword,
                'email' => $user->email,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kata laluan sementara baharu telah dihantar ke e-mel pengguna.',
            'temp_password' => $temporaryPassword,
            'email' => $user->email,
        ]);
    }

    public function toggleActive(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh menyahaktifkan akaun sendiri.',
            ], 422);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'Akaun telah diaktifkan.' : 'Akaun telah dinyahaktifkan.',
            'is_active' => $user->is_active,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh memadam akaun sendiri.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna telah dipadam.',
        ]);
    }

    public function destroyInvitation(UserInvitation $invitation): JsonResponse
    {
        $invitation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jemputan telah dibuang.',
        ]);
    }

    public function resendInvitation(UserInvitation $invitation): JsonResponse
    {
        $invitation->update([
            'token' => Str::random(64),
            'expires_at' => now()->addHours(24),
        ]);

        $fresh = $invitation->fresh();
        assert($fresh instanceof UserInvitation);

        defer(function () use ($fresh): void {
            try {
                Notification::route('mail', [$fresh->email => $fresh->name])
                    ->notify(new UserInvitationNotification($fresh));
            } catch (\Throwable $e) {
                Log::error('Gagal menghantar semula e-mel jemputan pengguna.', [
                    'invitation_id' => $fresh->id,
                    'email' => $fresh->email,
                    'exception' => $e->getMessage(),
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Jemputan telah dihantar semula.',
        ]);
    }
}
