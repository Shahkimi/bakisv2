<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckNoKpRequest;
use App\Http\Requests\RegisterMemberRequest;
use App\Http\Requests\RenewalPaymentRequest;
use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Services\MemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SemakController extends Controller
{
    public function __construct(
        private readonly MemberService $memberService
    ) {}

    public function index(Request $request): View
    {
        $jabatans = Jabatan::query()->where('is_active', true)->orderBy('nama_jabatan')->get();
        $jawatans = Jawatan::query()->where('is_active', true)->orderBy('nama_jawatan')->get();

        $prefillNoKp = session('no_kp', (string) $request->query('no_kp', ''));
        $showRegisterForm = session('show_register_form', false) || $request->boolean('register');

        return view('semak.index', compact('jabatans', 'jawatans', 'prefillNoKp', 'showRegisterForm'));
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

        return view('semak.result', [
            'result' => $result,
            'checkedNoKp' => $noKp,
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

        return view('semak.result', [
            'result' => $result,
            'checkedNoKp' => $noKp,
        ]);
    }

    public function bayar(RenewalPaymentRequest $request): RedirectResponse
    {
        $noKp = $request->validated('no_kp');

        try {
            $this->memberService->submitRenewalPayment($noKp, $request->validated());
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('semak.result', ['no_kp' => $noKp])
                ->with('error', 'Pembayaran tidak berjaya dihantar. Sila cuba lagi.');
        }

        return redirect()->route('semak.result', ['no_kp' => $noKp])
            ->with('success', 'Pembayaran pembaharuan telah dihantar. Sila tunggu pengesahan admin.');
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
}
