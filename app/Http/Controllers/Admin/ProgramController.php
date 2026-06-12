<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreProgramRequest;
use App\Http\Requests\Admin\Kawalan\UpdateProgramRequest;
use App\Models\Program;
use App\Services\ProgramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProgramController extends Controller
{
    public function __construct(
        private readonly ProgramService $programService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.program');
    }

    public function list(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(400, 'Invalid request');
        }

        $data = $this->programService->listOrdered()->map(fn (Program $program) => [
            'id' => $program->id,
            'nama_program' => $program->nama_program,
            'tarikh' => $program->tarikh->toDateString(),
            'tarikh_label' => $program->tarikh->translatedFormat('d M Y'),
            'waktu_mula' => $program->waktu_mula,
            'waktu_tamat' => $program->waktu_tamat,
            'is_past' => $program->isPast(),
            'is_active' => $program->is_active,
            'kehadiran' => $program->acara_id !== null,
            'acara_id' => $program->acara_id,
            'lokasi' => $program->acara?->lokasi,
            'expires_at' => $program->acara?->expires_at?->format('Y-m-d\TH:i'),
            'public_url' => $program->acara?->publicUrl(),
            'code' => $program->acara?->code,
        ]);

        return response()->json(['data' => $data]);
    }

    public function store(StoreProgramRequest $request): JsonResponse
    {
        $this->programService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Program berjaya ditambah.',
        ]);
    }

    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $this->programService->update($program, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Program telah dikemas kini.',
        ]);
    }

    public function destroy(Program $program): JsonResponse
    {
        $this->programService->delete($program);

        return response()->json([
            'success' => true,
            'message' => 'Program berjaya dipadam.',
        ]);
    }
}
