<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kod_jawatan' => ['required', 'string', 'max:10', 'unique:jawatans,kod_jawatan'],
            'nama_jawatan' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ], [
            'kod_jawatan.required' => 'Kod jawatan wajib diisi.',
            'kod_jawatan.unique' => 'Kod jawatan ini sudah wujud.',
            'nama_jawatan.required' => 'Nama jawatan wajib diisi.',
        ]);

        $jawatan = Jawatan::create($validated);

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

    public function update(Request $request, Jawatan $jawatan): JsonResponse
    {
        $validated = $request->validate([
            'kod_jawatan' => ['required', 'string', 'max:10', 'unique:jawatans,kod_jawatan,' . $jawatan->id],
            'nama_jawatan' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ], [
            'kod_jawatan.required' => 'Kod jawatan wajib diisi.',
            'kod_jawatan.unique' => 'Kod jawatan ini sudah wujud.',
            'nama_jawatan.required' => 'Nama jawatan wajib diisi.',
        ]);

        $jawatan->update($validated);

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
