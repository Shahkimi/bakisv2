<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class CarianService
{
    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Member::query()
            ->with(['memberStatus:id,name,code'])
            ->select(['id', 'no_ahli', 'nama', 'no_kp', 'member_status_id']);

        $query->withAktifThisYearExists();

        $this->applySearch($query, $request);
        $totalRecords = Member::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Member $member) => $this->formatRow($member)),
        ]);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }
        $term = '%'.addcslashes($searchValue, '%_\\').'%';
        $query->where(function (Builder $q) use ($term): void {
            $q->where('nama', 'like', $term)
                ->orWhere('no_ahli', 'like', $term)
                ->orWhere('no_kp', 'like', $term)
                ->orWhereHas('memberStatus', fn (Builder $b) => $b->where('name', 'like', $term));
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->latest('members.id');

            return;
        }
        $columns = [
            'no_ahli',
            'nama',
            'no_kp',
            'member_status_id',
        ];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'members.id';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = $request->integer('length', 10);

        if ($length === -1) {
            return $query->get();
        }

        return $query->skip($start)->take(min($length, 50))->get();
    }

    private function formatRow(Member $member): array
    {
        $user = auth()->user();
        $panel = $user instanceof User && $user->isAdmin() ? 'admin' : 'user';

        [$statusName, $statusCode] = $this->resolveListStatusDisplay($member);

        return [
            'no_ahli' => $member->no_ahli ?? '—',
            'nama' => e($member->nama),
            'no_kp' => e($member->no_kp),
            'status' => view($panel.'.carian.partials.status-indicator', [
                'name' => $statusName,
                'code' => $statusCode,
            ])->render(),
            'actions' => view($panel.'.carian.partials.actions', ['member' => $member])->render(),
        ];
    }

    /**
     * @return array{0: string, 1: string|null}
     */
    private function resolveListStatusDisplay(Member $member): array
    {
        $attrs = $member->getAttributes();
        $hasVerifiedPaymentThisYear = array_key_exists('aktif_this_year', $attrs)
            ? (bool) $attrs['aktif_this_year']
            : null;

        return $member->listStatusDisplayForCurrentYear($hasVerifiedPaymentThisYear);
    }
}
