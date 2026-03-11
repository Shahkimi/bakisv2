<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreYuranRequest;
use App\Http\Requests\Admin\Kawalan\UpdateYuranRequest;
use App\Models\Yuran;
use App\Services\YuranService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class YuranController extends Controller
{
    public function __construct(
        private readonly YuranService $yuranService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.yuran.index');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->yuranService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StoreYuranRequest $request): JsonResponse
    {
        $yuran = $this->yuranService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Yuran berjaya ditambah.',
            'data' => [
                'id' => $yuran->id,
                'jenis_yuran' => $yuran->jenis_yuran,
                'jumlah' => $yuran->jumlah,
                'is_active' => $yuran->is_active,
            ],
        ]);
    }

    public function update(UpdateYuranRequest $request, Yuran $yuran): JsonResponse
    {
        $this->yuranService->update($yuran, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Yuran telah dikemas kini.',
            'data' => [
                'id' => $yuran->id,
                'jenis_yuran' => $yuran->jenis_yuran,
                'jumlah' => $yuran->jumlah,
                'is_active' => $yuran->is_active,
            ],
        ]);
    }

    public function destroy(Yuran $yuran): JsonResponse
    {
        $yuran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yuran berjaya dipadam.',
        ]);
    }
}
