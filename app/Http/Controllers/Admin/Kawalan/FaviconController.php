<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Kawalan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreFaviconRequest;
use App\Http\Requests\Admin\Kawalan\StoreLogoRequest;
use App\Services\AcaraPdfService;
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
        return view('admin.kawalan.favicon', [
            'faviconUrl' => $this->siteSettingService->faviconPublicUrl(),
            'logoUrl' => $this->siteSettingService->logoPublicUrl(),
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

    public function storeLogo(StoreLogoRequest $request): JsonResponse
    {
        $this->siteSettingService->storeLogo($request->file('logo'));
        AcaraPdfService::forgetAllPosterCaches();

        return response()->json([
            'success' => true,
            'message' => 'Logo organisasi berjaya dikemas kini.',
            'url' => $this->siteSettingService->logoPublicUrl(),
        ]);
    }

    public function destroyLogo(): JsonResponse
    {
        $this->siteSettingService->clearLogo();
        AcaraPdfService::forgetAllPosterCaches();

        return response()->json([
            'success' => true,
            'message' => 'Logo organisasi telah dibuang.',
        ]);
    }
}
