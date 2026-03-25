<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Jawatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class JawatanService
{
    public function create(array $data): Jawatan
    {
        return Jawatan::create($data);
    }

    public function update(Jawatan $jawatan, array $data): Jawatan
    {
        $jawatan->update($data);

        return $jawatan;
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Jawatan::query()->select(['id', 'kod_jawatan', 'nama_jawatan', 'is_active']);

        $this->applySearch($query, $request);
        $totalRecords = Jawatan::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Jawatan $jawatan) => $this->formatRow($jawatan)),
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
            $q->where('kod_jawatan', 'like', $term)
                ->orWhere('nama_jawatan', 'like', $term);
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderBy('nama_jawatan', 'asc');

            return;
        }
        $columns = ['id', 'kod_jawatan', 'nama_jawatan', 'is_active'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'nama_jawatan';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    private function formatRow(Jawatan $jawatan): array
    {
        return [
            'id' => $jawatan->id,
            'kod_jawatan' => e($jawatan->kod_jawatan),
            'nama_jawatan' => e($jawatan->nama_jawatan),
            'is_active' => $jawatan->is_active,
            'actions' => [
                'id' => $jawatan->id,
                'kod_jawatan' => $jawatan->kod_jawatan,
                'nama_jawatan' => $jawatan->nama_jawatan,
                'is_active' => $jawatan->is_active,
            ],
        ];
    }
}
