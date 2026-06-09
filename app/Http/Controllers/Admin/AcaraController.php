<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreAcaraRequest;
use App\Http\Requests\Admin\Kawalan\UpdateAcaraRequest;
use App\Models\Acara;
use App\Services\AcaraPdfService;
use App\Services\AcaraService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class AcaraController extends Controller
{
    public function __construct(
        private readonly AcaraService $acaraService,
        private readonly AcaraPdfService $acaraPdfService,
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.acara');
    }

    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->acaraService->getDataTableData($request);
        }

        abort(400, 'Invalid request');
    }

    public function store(StoreAcaraRequest $request): JsonResponse
    {
        $acara = $this->acaraService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Acara berjaya ditambah.',
            'data' => [
                'id' => $acara->id,
                'nama_acara' => $acara->nama_acara,
                'code' => $acara->code,
                'public_url' => $acara->publicUrl(),
            ],
        ]);
    }

    public function update(UpdateAcaraRequest $request, Acara $acara): JsonResponse
    {
        $this->acaraService->update($acara, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Acara telah dikemas kini.',
            'data' => [
                'id' => $acara->id,
                'nama_acara' => $acara->nama_acara,
                'code' => $acara->code,
            ],
        ]);
    }

    public function destroy(Acara $acara): JsonResponse
    {
        $this->acaraService->delete($acara);

        return response()->json([
            'success' => true,
            'message' => 'Acara berjaya dipadam.',
        ]);
    }

    public function poster(Acara $acara): Response
    {
        ['content' => $content, 'filename' => $filename] = $this->acaraPdfService->renderPoster($acara);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function kehadiran(Acara $acara): View
    {
        return view('admin.kawalan.acara-kehadiran', [
            'acara' => $acara,
            'kehadiranCount' => $acara->kehadirans()->count(),
        ]);
    }

    public function kehadiranData(Request $request, Acara $acara): JsonResponse
    {
        if ($request->ajax()) {
            return $this->acaraService->getKehadiranDataTableData($acara, $request);
        }

        abort(400, 'Invalid request');
    }

    public function kehadiranPdf(Acara $acara): Response
    {
        ['content' => $content, 'filename' => $filename] = $this->acaraPdfService->renderAttendance($acara);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
