<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StorePerlembagaanRequest;
use App\Http\Requests\Admin\Kawalan\UpdatePerlembagaanRequest;
use App\Models\Perlembagaan;
use App\Services\PerlembagaanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PerlembagaanController extends Controller
{
    public function __construct(
        private readonly PerlembagaanService $perlembagaanService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.perlembagaan');
    }

    public function list(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(400, 'Invalid request');
        }

        $data = $this->perlembagaanService->listOrdered()->map(fn (Perlembagaan $p) => [
            'id' => $p->id,
            'tajuk' => $p->tajuk,
            'file_name' => $p->file_name,
            'file_size_label' => $p->humanFileSize(),
            'is_active' => $p->is_active,
            'url' => $p->publicUrl(),
        ]);

        return response()->json(['data' => $data]);
    }

    public function store(StorePerlembagaanRequest $request): JsonResponse
    {
        $this->perlembagaanService->create($request->validated(), $request->file('pdf'));

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berjaya ditambah.',
        ]);
    }

    public function update(UpdatePerlembagaanRequest $request, Perlembagaan $perlembagaan): JsonResponse
    {
        $this->perlembagaanService->update($perlembagaan, $request->validated(), $request->file('pdf'));

        return response()->json([
            'success' => true,
            'message' => 'Dokumen telah dikemas kini.',
        ]);
    }

    public function destroy(Perlembagaan $perlembagaan): JsonResponse
    {
        $this->perlembagaanService->delete($perlembagaan);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berjaya dipadam.',
        ]);
    }
}
