<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Kawalan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreMailSettingRequest;
use App\Services\MailSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

final class MailSettingController extends Controller
{
    public function __construct(
        private readonly MailSettingService $mailSetting,
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.emel', [
            'enabled' => $this->mailSetting->enabled(),
            'host' => $this->mailSetting->host(),
            'port' => $this->mailSetting->port(),
            'scheme' => $this->mailSetting->scheme(),
            'username' => $this->mailSetting->username(),
            'hasPassword' => $this->mailSetting->hasPassword(),
            'fromAddress' => $this->mailSetting->fromAddress(),
            'fromName' => $this->mailSetting->fromName(),
        ]);
    }

    public function update(StoreMailSettingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->mailSetting->update([
            'is_enabled' => (bool) $validated['is_enabled'],
            'host' => $validated['host'] ?? null,
            'port' => isset($validated['port']) ? (int) $validated['port'] : null,
            'scheme' => $validated['scheme'] ?? null,
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'from_address' => $validated['from_address'] ?? null,
            'from_name' => $validated['from_name'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tetapan e-mel berjaya disimpan.',
            'enabled' => $this->mailSetting->enabled(),
            'host' => $this->mailSetting->host(),
            'port' => $this->mailSetting->port(),
            'scheme' => $this->mailSetting->scheme(),
            'username' => $this->mailSetting->username(),
            'has_password' => $this->mailSetting->hasPassword(),
            'from_address' => $this->mailSetting->fromAddress(),
            'from_name' => $this->mailSetting->fromName(),
        ]);
    }

    public function test(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        $this->mailSetting->applyRuntimeConfig();
        Mail::purge('smtp');
        $this->mailSetting->allowNextSendWhileDisabled();

        try {
            Mail::raw(
                'Ini adalah e-mel ujian daripada sistem '.config('app.name').'. Jika anda menerima mesej ini, tetapan geganti SMTP berfungsi dengan baik.',
                function ($message) use ($validated): void {
                    $message->to($validated['test_email'])
                        ->subject('E-mel ujian — '.config('app.name'));
                }
            );
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghantar e-mel ujian: '.$e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'E-mel ujian berjaya dihantar ke '.$validated['test_email'].'.',
        ]);
    }
}
