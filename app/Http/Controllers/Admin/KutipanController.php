<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kutipan\AutocompleteMemberRequest;
use App\Http\Requests\Admin\Kutipan\CollectPaymentRequest;
use App\Http\Requests\Admin\Kutipan\SearchMemberRequest;
use App\Services\KutipanService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class KutipanController extends Controller
{
    public function __construct(
        private readonly KutipanService $kutipanService,
    ) {}

    public function index(): View
    {
        return view('admin.kutipan.index');
    }

    public function autocomplete(AutocompleteMemberRequest $request): JsonResponse
    {
        return $this->kutipanService->autocompleteMembers($request->validated()['search']);
    }

    public function member(string $encryptedNoKp): View|RedirectResponse
    {
        try {
            $data = $this->kutipanService->getMemberPageDataByEncryptedNoKp($encryptedNoKp);
        } catch (DecryptException) {
            return redirect()
                ->route('admin.kutipan.index')
                ->with('error', 'Pautan tidak sah.');
        }

        return view('admin.kutipan.member', [
            'yurans' => $this->kutipanService->getRenewalYurans(),
            'member' => $data['member'],
            'renewal' => $data['renewal'],
            'history' => $data['history'],
        ]);
    }

    public function search(SearchMemberRequest $request): JsonResponse
    {
        return $this->kutipanService->searchMembers($request->validated()['search']);
    }

    public function collect(CollectPaymentRequest $request): JsonResponse
    {
        return $this->kutipanService->collectPayment($request->validated());
    }
}
