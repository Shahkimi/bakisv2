<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class ProgramService
{
    public function __construct(
        private AcaraService $acaraService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Program
    {
        return DB::transaction(function () use ($data): Program {
            [$programData, $kehadiran, $acaraData] = $this->splitData($data);

            $program = Program::create($programData);

            if ($kehadiran) {
                $acara = $this->acaraService->create($this->acaraPayload($programData, $acaraData));
                $program->acara_id = $acara->id;
                $program->save();
            }

            return $program;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Program $program, array $data): Program
    {
        return DB::transaction(function () use ($program, $data): Program {
            [$programData, $kehadiran, $acaraData] = $this->splitData($data);

            $program->update($programData);

            if ($kehadiran) {
                $payload = $this->acaraPayload($programData, $acaraData);

                if ($program->acara_id !== null && $program->acara !== null) {
                    $this->acaraService->update($program->acara, $payload);
                } else {
                    $acara = $this->acaraService->create($payload);
                    $program->acara_id = $acara->id;
                    $program->save();
                }
            } elseif ($program->acara_id !== null && $program->acara !== null) {
                // Kehadiran turned off — remove the linked event.
                $this->acaraService->delete($program->acara);
                $program->acara_id = null;
                $program->save();
            }

            return $program;
        });
    }

    public function delete(Program $program): void
    {
        DB::transaction(function () use ($program): void {
            if ($program->acara_id !== null && $program->acara !== null) {
                $this->acaraService->delete($program->acara);
            }

            $program->delete();
        });
    }

    /**
     * Split the validated request into the Program's own columns, the Kehadiran
     * flag, and the extra Acara-only fields.
     *
     * @param  array<string, mixed>  $data
     * @return array{0: array<string, mixed>, 1: bool, 2: array<string, mixed>}
     */
    private function splitData(array $data): array
    {
        $kehadiran = filter_var($data['kehadiran'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $acaraData = [
            'lokasi' => $data['lokasi'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
        ];

        $programData = collect($data)
            ->only(['nama_program', 'tarikh', 'waktu_mula', 'waktu_tamat', 'is_active'])
            ->all();

        return [$programData, $kehadiran, $acaraData];
    }

    /**
     * Map the program data + Acara-only fields into an Acara payload.
     *
     * @param  array<string, mixed>  $programData
     * @param  array<string, mixed>  $acaraData
     * @return array<string, mixed>
     */
    private function acaraPayload(array $programData, array $acaraData): array
    {
        return [
            'nama_acara' => $programData['nama_program'],
            'lokasi' => $acaraData['lokasi'],
            'tarikh' => $programData['tarikh'],
            'waktu_mula' => $programData['waktu_mula'],
            'waktu_tamat' => $programData['waktu_tamat'],
            'expires_at' => $acaraData['expires_at'],
            'is_active' => $programData['is_active'],
        ];
    }

    /**
     * Programs ordered upcoming-first (soonest date first), then past (most recent first).
     *
     * @return Collection<int, Program>
     */
    public function listOrdered(bool $onlyActive = false): Collection
    {
        $query = Program::query()->with('acara');

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        $programs = $query->get();
        $today = Carbon::today();

        return $programs
            ->sortBy(function (Program $program) use ($today): string {
                $isPast = $program->tarikh->lt($today);
                // Upcoming (group 0) ascending by date; past (group 1) descending by date.
                $group = $isPast ? '1' : '0';
                $ts = $program->tarikh->timestamp;
                $key = $isPast ? (PHP_INT_MAX - $ts) : $ts;

                return $group.'_'.str_pad((string) $key, 20, '0', STR_PAD_LEFT);
            })
            ->values();
    }

    /** @return Collection<int, Program> */
    public function listForPublic(int $limit = 3): Collection
    {
        $upcoming = $this->listOrdered(onlyActive: true)
            ->filter(fn (Program $program) => ! $program->isPast());

        if ($upcoming->isNotEmpty()) {
            return $upcoming->take($limit)->values();
        }

        // No upcoming — fall back to the 3 most recently held programs.
        return $this->listOrdered(onlyActive: true)
            ->filter(fn (Program $program) => $program->isPast())
            ->take($limit)
            ->values();
    }
}
