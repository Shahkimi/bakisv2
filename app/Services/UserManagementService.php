<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final readonly class UserManagementService
{
    public function getDataTableData(Request $request): JsonResponse
    {
        $search = $request->input('search.value');

        $userRows = $this->queryUsers($search)->map(fn (User $user) => $this->formatUserRow($user));
        $invitationRows = $this->queryInvitations($search)->map(fn (UserInvitation $invitation) => $this->formatInvitationRow($invitation));

        /** @var Collection<int, array<string, mixed>> $merged */
        $merged = $userRows->concat($invitationRows);

        $orderColumn = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $merged = $this->applyOrdering($merged, $orderColumn, $orderDir);

        $recordsFiltered = $merged->count();
        $recordsTotal = User::count() + UserInvitation::count();

        $start = max(0, $request->integer('start', 0));
        $length = min($request->integer('length', 10), 100);
        $page = $merged->slice($start, $length)->values();

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $page,
        ]);
    }

    /**
     * @return Collection<int, User>
     */
    private function queryUsers(?string $search): Collection
    {
        $query = User::query()->select(['id', 'name', 'email', 'no_kp', 'role', 'email_verified_at', 'must_change_password', 'is_active', 'created_at']);

        if ($search !== null && $search !== '') {
            $term = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('no_kp', 'like', $term);
            });
        }

        return $query->get();
    }

    /**
     * @return Collection<int, UserInvitation>
     */
    private function queryInvitations(?string $search): Collection
    {
        $query = UserInvitation::query()->select(['id', 'name', 'email', 'role', 'expires_at', 'created_at']);

        if ($search !== null && $search !== '') {
            $term = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        return $query->get();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return Collection<int, array<string, mixed>>
     */
    private function applyOrdering(Collection $rows, int $orderColumn, string $orderDir): Collection
    {
        $columns = ['id', 'name', 'email', 'no_kp', 'role', 'status', 'actions'];
        $key = $columns[$orderColumn] ?? 'name';
        $desc = $orderDir === 'desc';

        if ($key === 'id') {
            return $rows->sortBy('sort_ts', SORT_REGULAR, $desc)->values();
        }

        if (in_array($key, ['name', 'email', 'no_kp', 'role', 'status'], true)) {
            return $rows->sortBy($key, SORT_NATURAL | SORT_FLAG_CASE, $desc)->values();
        }

        return $rows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE, false)->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function formatUserRow(User $user): array
    {
        $verified = $user->email_verified_at !== null;

        if (! $user->is_active) {
            $statusLabel = 'Dinyahaktif';
            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
        } elseif ($user->must_change_password) {
            $statusLabel = 'Kata laluan sementara';
            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
        } elseif ($verified) {
            $statusLabel = 'Disahkan';
            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';
        } else {
            $statusLabel = 'Belum disahkan';
            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
        }

        $roleLabel = $user->isAdmin() ? 'Admin' : 'Pengguna';
        $roleClass = $user->isAdmin()
            ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300'
            : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300';

        $noKpRaw = $user->no_kp;

        return [
            'row_type' => 'user',
            'id' => $user->id,
            'id_display' => 'U-'.$user->id,
            'name' => e($user->name),
            'email' => e($user->email),
            'no_kp' => $noKpRaw ?? '',
            'role' => $user->role,
            'role_label' => $roleLabel,
            'role_badge' => '<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full '.$roleClass.'">'.$roleLabel.'</span>',
            'status' => $statusLabel,
            'status_badge' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full '.$statusClass.'">'.$statusLabel.'</span>',
            'sort_ts' => $user->created_at?->timestamp ?? 0,
            'actions' => [
                'row_type' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'no_kp' => $noKpRaw ?? '',
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatInvitationRow(UserInvitation $invitation): array
    {
        $expired = $invitation->isExpired();
        $statusLabel = $expired ? 'Jemputan tamat tempoh' : 'Menunggu pendaftaran';
        $statusClass = $expired
            ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
            : 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300';

        $roleLabel = $invitation->role === User::ROLE_ADMIN ? 'Admin' : 'Pengguna';
        $roleClass = $invitation->role === User::ROLE_ADMIN
            ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300'
            : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300';

        return [
            'row_type' => 'invitation',
            'id' => $invitation->id,
            'id_display' => 'J-'.$invitation->id,
            'name' => e($invitation->name),
            'email' => e($invitation->email),
            'no_kp' => '',
            'role' => $invitation->role,
            'role_label' => $roleLabel,
            'role_badge' => '<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full '.$roleClass.'">'.$roleLabel.'</span>',
            'status' => $statusLabel,
            'status_badge' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full '.$statusClass.'">'.$statusLabel.'</span>',
            'sort_ts' => $invitation->created_at?->timestamp ?? 0,
            'actions' => [
                'row_type' => 'invitation',
                'id' => $invitation->id,
                'name' => $invitation->name,
                'email' => $invitation->email,
                'role' => $invitation->role,
                'expired' => $expired,
            ],
        ];
    }
}
