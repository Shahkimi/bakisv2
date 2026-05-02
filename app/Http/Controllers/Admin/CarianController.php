<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Services\CarianService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CarianController extends Controller
{
    use ResolvesPanel;

    public function __construct(
        private readonly CarianService $carianService
    ) {}

    public function index(): View
    {
        return view($this->panelView('members.index'));
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->carianService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }
}
