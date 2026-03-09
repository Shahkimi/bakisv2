<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PembayaranController extends Controller
{
    public function __construct(
        private readonly MemberService $memberService
    ) {}

    public function bukti(Payment $payment): StreamedResponse|RedirectResponse
    {
        if (! $payment->bukti_bayaran) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Tiada bukti pembayaran.');
        }

        $path = 'members/payments/'.basename($payment->bukti_bayaran);

        if (! Storage::disk('public')->exists($path)) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Fail bukti tidak dijumpai.');
        }

        return Storage::disk('public')->response($path, basename($path), [
            'Content-Type' => Storage::disk('public')->mimeType($path),
        ]);
    }

    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'pending');
        if ($statusFilter !== 'all' && ! in_array($statusFilter, ['pending', 'approved', 'rejected'], true)) {
            $statusFilter = 'pending';
        }

        $query = Payment::query()
            ->with(['member', 'yuran', 'approvedBy'])
            ->latest('payments.created_at');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.pembayaran.index', [
            'payments' => $payments,
            'statusFilter' => $statusFilter,
        ]);
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

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Pembayaran ini telah diproses.');
        }

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran telah ditolak.');
    }
}
