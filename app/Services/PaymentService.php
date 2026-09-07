<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

    public function requestWaiver(Payment $payment, string $reason, int $requestedById): void
    {
        $payment->update([
            'waiver_requested_by' => $requestedById,
            'waiver_requested_at' => now(),
            'waiver_reason' => $reason,
        ]);
    }

    public function declineWaiverRequest(Payment $payment): void
    {
        $payment->update([
            'waiver_requested_by' => null,
            'waiver_requested_at' => null,
            'waiver_reason' => null,
        ]);
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $statusFilter = $this->normalizeStatusFilter($request->string('status')->toString() ?: null);

        $query = Payment::query()
            ->with(['member', 'yuran', 'waiverRequestedBy:id,name', 'waivedBy:id,name', 'approvedBy:id,name'])
            ->select([
                'id',
                'member_id',
                'yuran_id',
                'tahun_bayar',
                'no_resit_sistem',
                'bukti_bayaran',
                'status',
                'approved_by',
                'approved_at',
                'catatan_admin',
                'waiver_requested_by',
                'waiver_requested_at',
                'waiver_reason',
                'waived_by',
                'waived_at',
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

    /**
     * Public-safe DataTables payload for one member's own payment history
     * (the "Sejarah Pembayaran" table on the /semak result page). Unlike
     * {@see getDataTableData()} this is scoped to a single member and never
     * exposes admin-only fields (catatan_admin, waiver details, approver
     * names) — the caller has already proven ownership via `no_kp` before
     * this is reached (see SemakController::paymentsData()).
     */
    public function getMemberPaymentsDataTable(Member $member, Request $request, ?string $checkedNoKp): JsonResponse
    {
        $query = Payment::query()
            ->where('member_id', $member->id)
            ->with('yuran:id,jenis_yuran,jumlah')
            ->select(['id', 'member_id', 'yuran_id', 'tahun_bayar', 'tahun_mula', 'tahun_tamat', 'status']);

        $totalRecords = (clone $query)->count();
        $filteredRecords = $totalRecords;

        $this->applyMemberPaymentsOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Payment $payment) => $this->formatMemberPaymentRow($payment, $checkedNoKp)),
        ]);
    }

    private function applyMemberPaymentsOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderByRaw('COALESCE(tahun_mula, tahun_bayar) desc')->orderByDesc('id');

            return;
        }

        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'asc' ? 'asc' : 'desc';

        // Jumlah/Jenis are accessors derived from the `yuran` relation, not real
        // columns, so only Tahun and Status (both marked orderable client-side)
        // can be ordered at the database level. Tahun orders by coverage year
        // (tahun_mula), not tahun_bayar (the year the payment was made) — a
        // multi-year renewal shares one tahun_bayar but distinct coverage years.
        if ($columnIndex === 0) {
            $query->orderByRaw("COALESCE(tahun_mula, tahun_bayar) {$dir}")->orderBy('id', $dir);

            return;
        }

        $columns = [3 => 'status'];
        $column = $columns[$columnIndex] ?? 'tahun_bayar';

        $query->orderBy($column, $dir)->orderBy('id', $dir);
    }

    private function formatMemberPaymentRow(Payment $payment, ?string $checkedNoKp): array
    {
        $jenisLabel = match ($payment->jenis) {
            'pendaftaran_baru' => 'Pendaftaran Baru',
            'pembaharuan' => 'Pembaharuan',
            default => $payment->yuran?->jenis_yuran ?? '–',
        };

        $receiptUrl = $payment->status === Payment::STATUS_APPROVED && filled($checkedNoKp)
            ? route('semak.payments.receipt', $payment).'?'.http_build_query(['no_kp' => $checkedNoKp])
            : null;

        return [
            'tahun_bayar' => $payment->tahun_bayar,
            'tahun_label' => $payment->coverageLabel(),
            'jumlah_formatted' => 'RM '.number_format((float) $payment->jumlah, 2),
            'jenis_label' => $jenisLabel,
            'status' => $payment->status,
            'receipt_url' => $receiptUrl,
        ];
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }

        $term = '%'.addcslashes((string) $searchValue, '%_\\').'%';

        $query->where(function (Builder $q) use ($term): void {
            $q->where('no_resit_sistem', 'like', $term)
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
            1 => 'no_resit_sistem',
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

    /** @return Collection<int, Payment> */
    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = $request->integer('length', 10);

        if ($length === -1) {
            return $query->get();
        }

        return $query->skip($start)->take(min($length, 50))->get();
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
                'route_key' => $payment->member?->getRouteKey(),
                'nama' => $payment->member?->nama ?? '–',
                'no_kp' => $payment->member?->no_kp ?? '–',
            ],
            'no_resit_sistem' => $payment->no_resit_sistem ?? '–',
            'tahun_bayar' => $payment->tahun_bayar,
            'jumlah' => (float) $payment->jumlah,
            'jumlah_formatted' => 'RM '.number_format((float) $payment->jumlah, 2),
            'jenis_label' => $jenisLabel,
            'status' => $payment->status,
            'bukti_bayaran' => (bool) $payment->bukti_bayaran,
            'bukti_bayaran_is_pdf' => $payment->bukti_bayaran
                ? strtolower(pathinfo($payment->bukti_bayaran, PATHINFO_EXTENSION)) === 'pdf'
                : false,
            'approved_by' => $payment->approvedBy?->name,
            'approved_at' => $payment->approved_at?->format('d/m/Y H:i'),
            'catatan_admin' => $payment->catatan_admin,
            'waiver_requested' => $payment->hasPendingWaiverRequest(),
            'waiver_requested_at' => $payment->waiver_requested_at?->format('d/m/Y H:i'),
            'waiver_requested_by' => $payment->waiverRequestedBy?->name,
            'waiver_reason' => $payment->waiver_reason,
            'waived_at' => $payment->waived_at?->format('d/m/Y H:i'),
            'waived_by' => $payment->waivedBy?->name,
        ];
    }

    private function normalizeStatusFilter(?string $statusFilter): string
    {
        $statusFilter = $statusFilter ?? 'all';

        if ($statusFilter === 'all') {
            return 'all';
        }

        $allowed = ['pending', 'approved', 'rejected', 'waived'];
        if (! in_array($statusFilter, $allowed, true)) {
            return 'pending';
        }

        return $statusFilter;
    }
}
