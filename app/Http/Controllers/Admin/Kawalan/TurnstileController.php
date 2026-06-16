<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Kawalan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreTurnstileRequest;
use App\Services\TurnstileSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class TurnstileController extends Controller
{
    public function __construct(
        private readonly TurnstileSettingService $turnstile,
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.keselamatan', [
            'enabled' => $this->turnstile->enabled(),
            'siteKey' => $this->turnstile->siteKey(),
            'hasSecret' => $this->turnstile->hasSecret(),
        ]);
    }

    public function update(StoreTurnstileRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->turnstile->update(
            (bool) $validated['is_enabled'],
            $validated['site_key'] ?? null,
            $validated['secret_key'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Tetapan Turnstile berjaya disimpan.',
            'enabled' => $this->turnstile->enabled(),
            'has_secret' => $this->turnstile->hasSecret(),
            'site_key' => $this->turnstile->siteKey(),
        ]);
    }
}
