<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreJabatanRequest;
use App\Http\Requests\Admin\Kawalan\UpdateJabatanRequest;
use App\Models\Jabatan;
use App\Services\JabatanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class JabatanController extends Controller
{
    public function __construct(
        private readonly JabatanService $jabatanService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.jabatan.index');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->jabatanService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StoreJabatanRequest $request): JsonResponse
    {
        $jabatan = $this->jabatanService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berjaya ditambah.',
            'data' => [
                'id' => $jabatan->id,
                'nama_jabatan' => $jabatan->nama_jabatan,
                'is_active' => $jabatan->is_active,
            ],
        ]);
    }

    public function update(UpdateJabatanRequest $request, Jabatan $jabatan): JsonResponse
    {
        $this->jabatanService->update($jabatan, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jabatan telah dikemas kini.',
            'data' => [
                'id' => $jabatan->id,
                'nama_jabatan' => $jabatan->nama_jabatan,
                'is_active' => $jabatan->is_active,
            ],
        ]);
    }

    public function destroy(Jabatan $jabatan): JsonResponse
    {
        // Optional: Check if jabatan has members before deleting to prevent constraint violation
        if ($jabatan->members()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh memadam jabatan yang mempunyai ahli berdaftar.',
            ], 422);
        }

        $jabatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berjaya dipadam.',
        ]);
    }
}
