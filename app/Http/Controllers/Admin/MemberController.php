<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMemberRequest;
use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Yuran;
use App\Services\MemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class MemberController extends Controller
{
    use ResolvesPanel;

    public function __construct(
        private readonly MemberService $memberService
    ) {}

    public function index(): View
    {
        return view($this->panelView('members.index'));
    }

    public function create(): View
    {
        $jabatans = Jabatan::where('is_active', true)->orderBy('nama_jabatan')->get();
        $jawatans = Jawatan::where('is_active', true)->orderBy('nama_jawatan')->get();
        $statuses = MemberStatus::where('is_active', true)->orderBy('name')->get();
        $yurans = Yuran::where('is_active', true)->orderBy('jenis_yuran')->get();

        return view($this->panelView('members.create'), compact('jabatans', 'jawatans', 'statuses', 'yurans'));
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['approve_immediately'] = $request->boolean('approve_immediately');
        $data['gambar'] = $request->file('gambar');
        $data['bukti_bayaran'] = $request->file('bukti_bayaran');
        $member = $this->memberService->createMemberByAdmin($data);

        return redirect()->to($this->panelRoute('members.edit', $member))
            ->with('success', 'Ahli berjaya ditambah.');
    }

    public function show(Member $member): RedirectResponse
    {
        return redirect()->to($this->panelRoute('members.edit', $member));
    }

    public function edit(Member $member): View
    {
        $member->load(['payments.yuran']);
        $jabatans = Jabatan::where('is_active', true)->orderBy('nama_jabatan')->get();
        $jawatans = Jawatan::where('is_active', true)->orderBy('nama_jawatan')->get();
        $statuses = MemberStatus::where('is_active', true)->orderBy('name')->get();

        return view($this->panelView('members.edit'), compact('member', 'jabatans', 'jawatans', 'statuses'));
    }

    public function update(StoreMemberRequest $request, Member $member): RedirectResponse|JsonResponse
    {
        $member->update($request->safe()->only([
            'no_ahli', 'jabatan_id', 'jawatan_id', 'member_status_id', 'nama', 'no_kp', 'email',
            'jantina', 'alamat1', 'alamat2', 'poskod', 'bandar', 'negeri', 'no_tel', 'no_hp',
            'catatan', 'tarikh_daftar',
        ]));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Ahli berjaya dikemaskini.',
                'redirect' => $this->panelRoute('members.edit', $member),
            ]);
        }

        return redirect()->to($this->panelRoute('members.edit', $member))
            ->with('success', 'Ahli berjaya dikemaskini.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->to($this->panelRoute('members.index'))
            ->with('success', 'Ahli berjaya dipadam.');
    }
}
