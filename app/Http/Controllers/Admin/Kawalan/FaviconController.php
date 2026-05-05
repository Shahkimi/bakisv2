<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Kawalan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreFaviconRequest;
use App\Services\SiteSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class FaviconController extends Controller
{
    public function __construct(
        private readonly SiteSettingService $siteSettingService,
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.favicon.index', [
            'faviconUrl' => $this->siteSettingService->faviconPublicUrl(),
        ]);
    }

    public function store(StoreFaviconRequest $request): JsonResponse
    {
        $this->siteSettingService->storeFavicon($request->file('favicon'));

        return response()->json([
            'success' => true,
            'message' => 'Favicon berjaya dikemas kini.',
            'url' => $this->siteSettingService->faviconPublicUrl(),
        ]);
    }

    public function destroy(): JsonResponse
    {
        $this->siteSettingService->clearFavicon();

        return response()->json([
            'success' => true,
            'message' => 'Favicon telah dibuang.',
        ]);
    }
}
