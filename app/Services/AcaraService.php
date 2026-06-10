<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Acara;
use App\Services\AcaraPdfService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class AcaraService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Acara
    {
        $data['code'] = Acara::generateUniqueCode();

        return Acara::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Acara $acara, array $data): Acara
    {
        // The public code is immutable once generated.
        unset($data['code']);

        $acara->update($data);

        // Bust cached poster so next download reflects the new data.
        AcaraPdfService::forgetPosterCache($acara->id);

        return $acara;
    }

    public function delete(Acara $acara): void
    {
        AcaraPdfService::forgetPosterCache($acara->id);

        $acara->delete();
    }

    /**
     * Server-side DataTables payload of attendees for a single event.
     */
    public function getKehadiranDataTableData(Acara $acara, Request $request): JsonResponse
    {
        $base = $acara->kehadirans();

        $query = $acara->kehadirans()->select(['id', 'acara_id', 'nama', 'no_kp', 'attended_at']);

        $searchValue = $request->input('search.value');
        if (is_string($searchValue) && $searchValue !== '') {
            $term = '%'.addcslashes($searchValue, '%_\\').'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('nama', 'like', $term)->orWhere('no_kp', 'like', $term);
            });
        }

        $totalRecords = $base->count();
        $filteredRecords = (clone $query)->count();
        $query->orderBy('attended_at');
        $data = $this->getPaginatedData($query, $request);

        $start = $request->integer('start', 0);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->map(fn ($hadir, $index) => [
                'no' => $start + $index + 1,
                'nama' => e($hadir->nama),
                'no_kp' => e($hadir->no_kp),
                'attended_at' => $hadir->attended_at->translatedFormat('d M Y, g:i A'),
            ]),
        ]);
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Acara::query()
            ->withCount('kehadirans')
            ->select(['id', 'nama_acara', 'lokasi', 'tarikh', 'waktu_mula', 'waktu_tamat', 'code', 'expires_at', 'is_active']);

        $this->applySearch($query, $request);
        $totalRecords = Acara::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Acara $acara) => $this->formatRow($acara)),
        ]);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }
        $term = '%'.addcslashes($searchValue, '%_\\').'%';
        $query->where(function (Builder $q) use ($term): void {
            $q->where('nama_acara', 'like', $term)
                ->orWhere('lokasi', 'like', $term)
                ->orWhere('code', 'like', $term);
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderByDesc('expires_at');

            return;
        }
        // Column indices match the DataTables columns: 0=nama_acara, 1=code, 2=expires_at, 3=kehadiran_count, 4=actions
        $columns = ['nama_acara', 'code', 'expires_at', 'kehadiran_count', 'actions'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'expires_at';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function formatRow(Acara $acara): array
    {
        $isExpired = $acara->isExpired();

        return [
            'id'                  => $acara->id,
            'nama_acara'          => e($acara->nama_acara),
            'lokasi'              => e($acara->lokasi),
            'tarikh'              => $acara->tarikh?->translatedFormat('d M Y'),
            'waktu_mula'          => e($acara->waktu_mula ?? ''),
            'waktu_tamat'         => e($acara->waktu_tamat ?? ''),
            'code'                => e($acara->code),
            'public_url'          => $acara->publicUrl(),
            'expires_at'          => $acara->expires_at->toIso8601String(),
            'expires_at_formatted'=> $acara->expires_at->translatedFormat('d M Y, g:i A'),
            'is_active'           => $acara->is_active,
            'is_expired'          => $isExpired,
            'is_open'             => $acara->isOpen(),
            'kehadiran_count'     => $acara->kehadirans_count,
            'actions' => [
                'id'          => $acara->id,
                'nama_acara'  => $acara->nama_acara,
                'lokasi'      => $acara->lokasi,
                'tarikh'      => $acara->tarikh?->format('Y-m-d'),
                'waktu_mula'  => $acara->waktu_mula,
                'waktu_tamat' => $acara->waktu_tamat,
                'code'        => $acara->code,
                'public_url'  => $acara->publicUrl(),
                'expires_at'  => $acara->expires_at->format('Y-m-d\TH:i'),
                'is_active'   => $acara->is_active,
            ],
        ];
    }
}
