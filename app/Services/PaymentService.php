<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class PaymentService
{
    /**
     * @return array{payments: LengthAwarePaginator, statusFilter: string}
     */
    public function listForAdmin(?string $statusFilter): array
    {
        $statusFilter = $this->normalizeStatusFilter($statusFilter);

        $query = Payment::query()
            ->with(['member', 'yuran', 'approvedBy'])
            ->latest('payments.created_at');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return [
            'payments' => $query->paginate(15)->withQueryString(),
            'statusFilter' => $statusFilter,
        ];
    }

    public function rejectPendingPayment(Payment $payment, ?string $catatanAdmin, ?int $approvedById): void
    {
        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'approved_by' => $approvedById,
            'approved_at' => now(),
            'catatan_admin' => $catatanAdmin,
        ]);
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $statusFilter = $this->normalizeStatusFilter($request->string('status')->toString() ?: null);

        $query = Payment::query()
            ->with(['member', 'yuran'])
            ->select([
                'id',
                'member_id',
                'yuran_id',
                'tahun_bayar',
                'no_resit_transfer',
                'bukti_bayaran',
                'status',
            ]);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $jabatanFilter = $request->input('jabatan_filter');
        if ($jabatanFilter !== null && $jabatanFilter !== '') {
            $query->whereHas('member', fn (Builder $q) => $q->where('jabatan_id', (int) $jabatanFilter));
        }

        $this->applySearch($query, $request);

        $totalRecords = Payment::count();
        $filteredRecords = (clone $query)->count();

        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Payment $payment) => $this->formatRow($payment)),
        ]);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }

        $term = '%'.addcslashes((string) $searchValue, '%_\\').'%';

        $query->where(function (Builder $q) use ($term): void {
            $q->where('no_resit_transfer', 'like', $term)
                ->orWhere('tahun_bayar', 'like', $term)
                ->orWhereHas('member', function (Builder $memberQ) use ($term): void {
                    $memberQ->where('nama', 'like', $term)
                        ->orWhere('no_kp', 'like', $term);
                });
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderByDesc('tahun_bayar')->orderByDesc('id');

            return;
        }

        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'asc' ? 'asc' : 'desc';

        $columns = [
            0 => 'member',      // Maklumat Ahli – order by member nama
            1 => 'no_resit_transfer',
            2 => 'status',
            3 => 'id',          // Tindakan – non-orderable, fallback to id
        ];

        $column = $columns[$columnIndex] ?? 'id';

        if ($column === 'member') {
            $query->join('members', 'payments.member_id', '=', 'members.id')
                ->orderBy('members.nama', $dir)
                ->orderBy('payments.id', $dir);
        } else {
            $query->orderBy('payments.'.$column, $dir)->orderBy('payments.id', $dir);
        }
    }

    /** @return \Illuminate\Support\Collection<int, Payment> */
    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    private function formatRow(Payment $payment): array
    {
        $jenisLabel = match ($payment->jenis) {
            'pendaftaran_baru' => 'Pendaftaran Baru',
            'pembaharuan' => 'Pembaharuan',
            default => $payment->yuran?->jenis_yuran ?? '–',
        };

        return [
            'id' => $payment->id,
            'member' => [
                'id' => $payment->member?->id,
                'nama' => $payment->member?->nama ?? '–',
                'no_kp' => $payment->member?->no_kp ?? '–',
            ],
            'no_resit_transfer' => $payment->no_resit_transfer ?? '–',
            'tahun_bayar' => $payment->tahun_bayar,
            'jumlah' => (float) $payment->jumlah,
            'jumlah_formatted' => 'RM '.number_format((float) $payment->jumlah, 2),
            'jenis_label' => $jenisLabel,
            'status' => $payment->status,
            'bukti_bayaran' => (bool) $payment->bukti_bayaran,
        ];
    }

    private function normalizeStatusFilter(?string $statusFilter): string
    {
        $statusFilter = $statusFilter ?? 'pending';

        if ($statusFilter === 'all') {
            return 'all';
        }

        $allowed = ['pending', 'approved', 'rejected'];
        if (! in_array($statusFilter, $allowed, true)) {
            return 'pending';
        }

        return $statusFilter;
    }
}
