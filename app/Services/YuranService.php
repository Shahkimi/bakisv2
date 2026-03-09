<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Yuran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class YuranService
{
    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Yuran::query()->select(['id', 'jenis_yuran', 'jumlah', 'is_active']);

        $this->applySearch($query, $request);
        $totalRecords = Yuran::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (Yuran $yuran) => $this->formatRow($yuran)),
        ]);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }
        $term = '%' . addcslashes($searchValue, '%_\\') . '%';
        $query->where('jenis_yuran', 'like', $term);
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (!$order || !isset($order['column'], $order['dir'])) {
            $query->orderBy('jenis_yuran', 'asc');
            return;
        }
        $columns = ['id', 'jenis_yuran', 'jumlah', 'is_active'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'jenis_yuran';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    private function formatRow(Yuran $yuran): array
    {
        return [
            'id' => $yuran->id,
            'jenis_yuran' => e($yuran->jenis_yuran),
            'jumlah' => $yuran->jumlah,
            'jumlah_formatted' => 'RM ' . number_format((float) $yuran->jumlah, 2),
            'is_active' => $yuran->is_active,
            'actions' => [
                'id' => $yuran->id,
                'jenis_yuran' => $yuran->jenis_yuran,
                'jumlah' => $yuran->jumlah,
                'is_active' => $yuran->is_active,
            ],
        ];
    }
}
