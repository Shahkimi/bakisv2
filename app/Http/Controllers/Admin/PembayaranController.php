<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pembayaran\RejectPaymentRequest;
use App\Models\Jabatan;
use App\Models\Payment;
use App\Services\MemberService;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PembayaranController extends Controller
{
    public function __construct(
        private readonly MemberService $memberService,
        private readonly PaymentService $paymentService,
    ) {}

    public function bukti(Payment $payment): StreamedResponse|RedirectResponse
    {
        if (! $payment->bukti_bayaran) {
            abort(404);
        }

        $filename = basename($payment->bukti_bayaran);
        $path = 'members/payments/'.$filename;

        $localDisk = Storage::disk('local');
        if ($localDisk->exists($path)) {
            return $localDisk->response($path, $filename, [
                'Content-Type' => $localDisk->mimeType($path),
            ]);
        }

        // Backward compatibility for proofs already uploaded to the public disk.
        $publicDisk = Storage::disk('public');
        if ($publicDisk->exists($path)) {
            return $publicDisk->response($path, $filename, [
                'Content-Type' => $publicDisk->mimeType($path),
            ]);
        }

        abort(404);
    }

    public function index(Request $request): View
    {
        ['payments' => $payments, 'statusFilter' => $statusFilter] = $this->paymentService
            ->listForAdmin($request->query('status'));

        $jabatans = Jabatan::where('is_active', true)
            ->orderBy('nama_jabatan')
            ->get();

        return view('admin.pembayaran.index', [
            'payments' => $payments,
            'statusFilter' => $statusFilter,
            'jabatans' => $jabatans,
        ]);
    }

    public function getData(Request $request): JsonResponse
    {
        $isDataTablesRequest = $request->ajax()
            || $request->wantsJson()
            || $request->has('draw');

        if ($isDataTablesRequest) {
            return $this->paymentService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function getPendingCount(Request $request): JsonResponse
    {
        $query = Payment::query()->where('status', Payment::STATUS_PENDING);

        $jabatanFilter = $request->input('jabatan_filter');
        if ($jabatanFilter !== null && $jabatanFilter !== '') {
            $query->whereHas('member', fn (Builder $q) => $q->where('jabatan_id', (int) $jabatanFilter));
        }

        return response()->json(['count' => $query->count()]);
    }

    public function approve(Payment $payment): RedirectResponse
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Pembayaran ini telah diproses.');
        }

        $this->memberService->approvePayment($payment);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran telah disahkan. Status ahli telah dikemas kini kepada Aktif.');
    }

    public function reject(RejectPaymentRequest $request, Payment $payment): RedirectResponse
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Pembayaran ini telah diproses.');
        }

        $this->paymentService->rejectPendingPayment(
            payment: $payment,
            catatanAdmin: $request->validated()['catatan_admin'] ?? null,
            approvedById: auth()->id(),
        );

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran telah ditolak.');
    }
}
