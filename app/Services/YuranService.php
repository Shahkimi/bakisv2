<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Yuran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class YuranService
{
    public function create(array $data): Yuran
    {
        return Yuran::create($data);
    }

    public function update(Yuran $yuran, array $data): Yuran
    {
        unset($data['code']);

        $yuran->update($data);

        return $yuran;
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $query = Yuran::query()->select(['id', 'jenis_yuran', 'code', 'jumlah', 'is_active', 'is_show']);

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
        $term = '%'.addcslashes($searchValue, '%_\\').'%';
        $query->where(function (Builder $q) use ($term): void {
            $q->where('jenis_yuran', 'like', $term)
                ->orWhere('code', 'like', $term);
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderBy('jenis_yuran', 'asc');

            return;
        }
        $columns = ['id', 'jenis_yuran', 'code', 'jumlah', 'is_active'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'jenis_yuran';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = $request->integer('length', 10);

        if ($length === -1) {
            return $query->get();
        }

        return $query->skip($start)->take(min($length, 50))->get();
    }

    private function formatRow(Yuran $yuran): array
    {
        return [
            'id' => $yuran->id,
            'jenis_yuran' => e($yuran->jenis_yuran),
            'code' => e($yuran->code ?? ''),
            'jumlah' => $yuran->jumlah,
            'jumlah_formatted' => 'RM '.number_format((float) $yuran->jumlah, 2),
            'is_active' => $yuran->is_active,
            'is_show' => $yuran->is_show,
            'is_system_defined' => $yuran->isSystemDefined(),
            'actions' => [
                'id' => $yuran->id,
                'jenis_yuran' => $yuran->jenis_yuran,
                'code' => $yuran->code,
                'jumlah' => $yuran->jumlah,
                'is_active' => $yuran->is_active,
                'is_show' => $yuran->is_show,
                'is_system_defined' => $yuran->isSystemDefined(),
            ],
        ];
    }
}
