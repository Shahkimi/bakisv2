<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StorePaymentAccountRequest;
use App\Http\Requests\Admin\Kawalan\UpdatePaymentAccountRequest;
use App\Models\PaymentAccount;
use App\Services\PaymentAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PaymentAccountController extends Controller
{
    public function __construct(
        private readonly PaymentAccountService $paymentAccountService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.Account.index');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->paymentAccountService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StorePaymentAccountRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $account = $this->paymentAccountService->create($validated, $request->file('qr_image'));

        return response()->json([
            'success' => true,
            'message' => 'Akaun bayaran berjaya ditambah.',
            'data' => [
                'id' => $account->id,
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'is_active' => $account->is_active,
            ],
        ]);
    }

    public function update(UpdatePaymentAccountRequest $request, PaymentAccount $paymentAccount): JsonResponse
    {
        $validated = $request->validated();
        $this->paymentAccountService->update($paymentAccount, $validated, $request->file('qr_image'));

        return response()->json([
            'success' => true,
            'message' => 'Akaun bayaran telah dikemas kini.',
            'data' => [
                'id' => $paymentAccount->id,
                'account_name' => $paymentAccount->account_name,
                'account_number' => $paymentAccount->account_number,
                'is_active' => $paymentAccount->is_active,
            ],
        ]);
    }

    public function destroy(PaymentAccount $paymentAccount): JsonResponse
    {
        $this->paymentAccountService->delete($paymentAccount);

        return response()->json([
            'success' => true,
            'message' => 'Akaun bayaran berjaya dipadam.',
        ]);
    }

    public function showQr(PaymentAccount $paymentAccount): StreamedResponse|JsonResponse
    {
        if (empty($paymentAccount->qr_image_path) || ! Storage::disk('local')->exists($paymentAccount->qr_image_path)) {
            abort(404);
        }

        return Storage::disk('local')->response(
            $paymentAccount->qr_image_path,
            $paymentAccount->account_name.'-qr.'.pathinfo($paymentAccount->qr_image_path, PATHINFO_EXTENSION),
            ['Content-Type' => Storage::disk('local')->mimeType($paymentAccount->qr_image_path)]
        );
    }
}
