<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class JabatanService
{
    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Jabatan::query()->select(['id', 'nama_jabatan', 'is_active']);

        $this->applySearch($query, $request);
        $totalRecords = Jabatan::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Jabatan $jabatan) => $this->formatRow($jabatan)),
        ]);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }
        $term = '%' . addcslashes($searchValue, '%_\\') . '%';
        $query->where('nama_jabatan', 'like', $term);
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (!$order || !isset($order['column'], $order['dir'])) {
            $query->orderBy('nama_jabatan', 'asc');
            return;
        }
        $columns = ['id', 'nama_jabatan', 'is_active'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'nama_jabatan';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    private function formatRow(Jabatan $jabatan): array
    {
        return [
            'id' => $jabatan->id,
            'nama_jabatan' => e($jabatan->nama_jabatan),
            'is_active' => $jabatan->is_active,
            'actions' => [
                'id' => $jabatan->id,
                'nama_jabatan' => $jabatan->nama_jabatan,
                'is_active' => $jabatan->is_active,
            ],
        ];
    }
}
