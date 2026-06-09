<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\TidakAktifSearchRequest;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $currentYear = (int) date('Y');
        $counts = $this->dashboardService->getStatusCounts($currentYear);

        return view('dashboard', [
            'aktifCount' => $counts['aktif'],
            'tidakAktifCount' => $counts['tidak_aktif'],
            'meninggalCount' => $counts['meninggal'],
            'totalCount' => $counts['total'],
            'currentYear' => $currentYear,
        ]);
    }

    public function tidakAktif(TidakAktifSearchRequest $request): JsonResponse
    {
        $search = $request->validated()['search'] ?? null;
        $search = is_string($search) && trim($search) !== '' ? trim($search) : null;

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getTidakAktifMembers($search),
        ]);
    }
}
