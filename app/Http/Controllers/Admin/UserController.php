<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserInvitationRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserInvitationNotification;
use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('admin.kawalan.pengguna.index');
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

        $invitation = DB::transaction(function () use ($data): UserInvitation {
            return UserInvitation::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'token' => Str::random(64),
                'role' => $data['role'],
                'expires_at' => now()->addHours(24),
            ]);
        });

        Notification::route('mail', [$invitation->email => $invitation->name])
            ->notify(new UserInvitationNotification($invitation));

        return response()->json([
            'success' => true,
            'message' => 'Jemputan telah dihantar ke e-mel.',
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

        Notification::route('mail', [$fresh->email => $fresh->name])
            ->notify(new UserInvitationNotification($fresh));

        return response()->json([
            'success' => true,
            'message' => 'Jemputan telah dihantar semula.',
        ]);
    }
}
