<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckNoKpRequest;
use App\Http\Requests\RegisterMemberRequest;
use App\Http\Requests\RenewalPaymentRequest;
use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Services\MemberService;
use App\Services\PaymentReceiptPdfService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SemakController extends Controller
{
    public function __construct(
        private readonly MemberService $memberService,
        private readonly PaymentReceiptPdfService $paymentReceiptPdfService,
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request): View
    {
        $jabatans = Jabatan::query()->where('is_active', true)->orderBy('nama_jabatan')->get();
        $jawatans = Jawatan::query()->where('is_active', true)->orderBy('nama_jawatan')->get();

        $prefillNoKp = session('no_kp', (string) $request->query('no_kp', ''));
        $showRegisterForm = session('show_register_form', false) || $request->boolean('register');

        $willShowRegisterForm = $showRegisterForm || $request->old('nama') !== null;
        $paymentAccounts = $willShowRegisterForm ? $this->getActivePaymentAccountsForSemak() : collect();

        return view('semak.index', compact('jabatans', 'jawatans', 'prefillNoKp', 'showRegisterForm', 'paymentAccounts'));
    }

    public function check(CheckNoKpRequest $request): View|RedirectResponse
    {
        $noKp = $request->validated('no_kp');
        $result = $this->memberService->checkMemberStatus($noKp);

        if ($result['status'] === 'not_found') {
            return redirect()->route('semak.index')
                ->with('no_kp', $noKp)
                ->with('show_register_form', true)
                ->with('lookup_alert', [
                    'type' => 'warning',
                    'title' => 'No. KP tidak ditemui',
                    'message' => 'Sila lengkapkan borang pendaftaran ahli baharu di bawah.',
                ]);
        }

        if (isset($result['member'])) {
            $result['member']->load('payments.yuran');
        }

        $paymentAccounts = $this->getActivePaymentAccountsForSemak();

        return view('semak.result', [
            'result' => $result,
            'checkedNoKp' => $noKp,
            'paymentAccounts' => $paymentAccounts,
        ]);
    }

    public function showResult(Request $request): View|RedirectResponse
    {
        $validated = $request->validate(['no_kp' => ['required', 'digits:12']]);
        $noKp = $validated['no_kp'];
        $result = $this->memberService->checkMemberStatus($noKp);

        if ($result['status'] === 'not_found') {
            return redirect()->route('semak.index')
                ->with('no_kp', $noKp)
                ->with('lookup_alert', [
                    'type' => 'warning',
                    'title' => 'No. KP tidak ditemui',
                    'message' => 'Sila semak No. KP atau daftar ahli baharu.',
                ]);
        }

        if (isset($result['member'])) {
            $result['member']->load('payments.yuran');
        }

        $paymentAccounts = $this->getActivePaymentAccountsForSemak();

        return view('semak.result', [
            'result' => $result,
            'checkedNoKp' => $noKp,
            'paymentAccounts' => $paymentAccounts,
        ]);
    }

    public function downloadPaymentReceipt(Request $request, Payment $payment): Response
    {
        $noKpRaw = $request->query('no_kp');
        if (! is_string($noKpRaw)) {
            abort(404);
        }
        $queryKp = preg_replace('/\D/', '', $noKpRaw);
        if (strlen($queryKp) !== 12) {
            abort(404);
        }

        if ($payment->status !== Payment::STATUS_APPROVED) {
            abort(404);
        }

        $payment->loadMissing('member');
        $member = $payment->member;
        if ($member === null) {
            abort(404);
        }

        $memberKp = preg_replace('/\D/', '', (string) $member->no_kp);
        if ($queryKp === '' || $memberKp === '' || $queryKp !== $memberKp) {
            abort(404);
        }

        ['content' => $content, 'filename' => $filename] = $this->paymentReceiptPdfService->render($payment);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Server-side DataTables payload for the "Sejarah Pembayaran" table on the
     * public semak result page. Scoped strictly to the member matching the
     * given `no_kp` — same proof-of-ownership requirement as
     * {@see downloadPaymentReceipt()} — so this can never be used to browse
     * another member's payment history.
     */
    public function paymentsData(Request $request): JsonResponse
    {
        $noKpRaw = $request->query('no_kp');
        $noKp = is_string($noKpRaw) ? preg_replace('/\D/', '', $noKpRaw) : '';

        $member = strlen((string) $noKp) === 12
            ? Member::where('no_kp', $noKp)->first()
            : null;

        if (! $member) {
            return response()->json([
                'draw' => $request->integer('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }

        return $this->paymentService->getMemberPaymentsDataTable($member, $request, $noKp);
    }

    public function bayar(RenewalPaymentRequest $request): RedirectResponse
    {
        $noKp = $request->validated('no_kp');

        try {
            $this->memberService->submitRenewalPayment($noKp, $request->validated());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('semak.result', ['no_kp' => $noKp])
                ->with('error', 'Pembayaran tidak berjaya dihantar. Sila cuba lagi.');
        }

        $yearCount = count($request->validated('years'));

        return redirect()->route('semak.result', ['no_kp' => $noKp])
            ->with(
                'success',
                $yearCount === 1
                    ? 'Pembayaran pembaharuan telah dihantar. Sila tunggu pengesahan admin.'
                    : "Pembayaran pembaharuan untuk {$yearCount} tahun telah dihantar. Sila tunggu pengesahan admin."
            );
    }

    public function register(RegisterMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tarikh_daftar'] = now()->toDateString();

        try {
            $member = $this->memberService->registerNewMember($data);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('semak.index')
                ->withInput()
                ->with('no_kp', $data['no_kp'] ?? '')
                ->with('show_register_form', true)
                ->with('lookup_alert', [
                    'type' => 'error',
                    'title' => 'Pendaftaran tidak berjaya',
                    'message' => 'Ralat berlaku semasa menghantar pendaftaran. Sila semak semula maklumat dan cuba lagi.',
                ]);
        }

        return redirect()->route('semak.success')
            ->with('member', $member)
            ->with('success_message', 'Pendaftaran berjaya dihantar. Permohonan anda sedang diproses.');
    }

    public function success(): View
    {
        $member = session('member');
        if (! $member) {
            return view('semak.success', ['member' => null]);
        }

        return view('semak.success', ['member' => $member]);
    }

    /**
     * Active payment accounts with temporary signed QR URLs (semak-only display).
     *
     * @return Collection<int, object{id: int, account_name: string, account_number: string, qr_image_url: string|null}>
     */
    private function getActivePaymentAccountsForSemak(): Collection
    {
        return PaymentAccount::query()
            ->active()
            ->orderBy('account_name')
            ->get()
            ->map(function (PaymentAccount $account) {
                return (object) [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'account_number' => $account->account_number,
                    'qr_image_url' => $account->getTemporaryQrUrl(60),
                ];
            });
    }

    /**
     * Stream QR image for payment account (signed URL only, for semak views).
     */
    public function showQr(PaymentAccount $paymentAccount): StreamedResponse
    {
        if (empty($paymentAccount->qr_image_path)
            || ! $paymentAccount->show_qr
            || ! Storage::disk('local')->exists($paymentAccount->qr_image_path)) {
            abort(404);
        }

        return Storage::disk('local')->response(
            $paymentAccount->qr_image_path,
            $paymentAccount->account_name.'-qr.'.pathinfo($paymentAccount->qr_image_path, PATHINFO_EXTENSION),
            ['Content-Type' => Storage::disk('local')->mimeType($paymentAccount->qr_image_path)]
        );
    }
}
