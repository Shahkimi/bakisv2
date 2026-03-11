<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreJawatanRequest;
use App\Http\Requests\Admin\Kawalan\UpdateJawatanRequest;
use App\Models\Jawatan;
use App\Services\JawatanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class JawatanController extends Controller
{
    public function __construct(
        private readonly JawatanService $jawatanService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.jawatan.index');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->jawatanService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StoreJawatanRequest $request): JsonResponse
    {
        $jawatan = $this->jawatanService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jawatan berjaya ditambah.',
            'data' => [
                'id' => $jawatan->id,
                'kod_jawatan' => $jawatan->kod_jawatan,
                'nama_jawatan' => $jawatan->nama_jawatan,
                'is_active' => $jawatan->is_active,
            ],
        ]);
    }

    public function update(UpdateJawatanRequest $request, Jawatan $jawatan): JsonResponse
    {
        $this->jawatanService->update($jawatan, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jawatan telah dikemas kini.',
            'data' => [
                'id' => $jawatan->id,
                'kod_jawatan' => $jawatan->kod_jawatan,
                'nama_jawatan' => $jawatan->nama_jawatan,
                'is_active' => $jawatan->is_active,
            ],
        ]);
    }

    public function destroy(Jawatan $jawatan): JsonResponse
    {
        if ($jawatan->members()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh memadam jawatan yang mempunyai ahli berdaftar.',
            ], 422);
        }

        $jawatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jawatan berjaya dipadam.',
        ]);
    }
}
