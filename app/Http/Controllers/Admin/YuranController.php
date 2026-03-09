<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jenis_yuran' => ['required', 'string', 'max:255', 'unique:yurans,jenis_yuran'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ], [
            'jenis_yuran.required' => 'Jenis yuran wajib diisi.',
            'jenis_yuran.unique' => 'Jenis yuran ini sudah wujud.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah mesti nombor.',
            'jumlah.min' => 'Jumlah mesti sekurang-kurangnya 0.',
        ]);

        $yuran = Yuran::create($validated);

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

    public function update(Request $request, Yuran $yuran): JsonResponse
    {
        $validated = $request->validate([
            'jenis_yuran' => ['required', 'string', 'max:255', 'unique:yurans,jenis_yuran,' . $yuran->id],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ], [
            'jenis_yuran.required' => 'Jenis yuran wajib diisi.',
            'jenis_yuran.unique' => 'Jenis yuran ini sudah wujud.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah mesti nombor.',
            'jumlah.min' => 'Jumlah mesti sekurang-kurangnya 0.',
        ]);

        $yuran->update($validated);

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
