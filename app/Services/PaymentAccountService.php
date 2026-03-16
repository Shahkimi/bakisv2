<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PaymentAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class PaymentAccountService
{
    private const QR_DISK = 'local';

    private const QR_DIRECTORY = 'qr-codes';

    public function create(array $data, ?UploadedFile $qrImage): PaymentAccount
    {
        $path = null;
        if ($qrImage !== null) {
            $path = $this->storeQrImage($qrImage);
            $data['qr_image_path'] = $path;
        }

        return PaymentAccount::create($data);
    }

    public function update(PaymentAccount $account, array $data, ?UploadedFile $qrImage = null): PaymentAccount
    {
        if ($qrImage !== null) {
            $this->deleteQrImage($account->qr_image_path);
            $data['qr_image_path'] = $this->storeQrImage($qrImage);
        }

        $account->update($data);

        return $account;
    }

    public function delete(PaymentAccount $account): void
    {
        $this->deleteQrImage($account->qr_image_path);
        $account->delete();
    }

    public function getDataTableData(Request $request): JsonResponse
    {
        $query = PaymentAccount::query()->select(['id', 'account_name', 'account_number', 'qr_image_path', 'is_active']);

        $this->applySearch($query, $request);
        $totalRecords = PaymentAccount::count();
        $filteredRecords = (clone $query)->count();
        $this->applyOrdering($query, $request);
        $data = $this->getPaginatedData($query, $request);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->map(fn (PaymentAccount $account) => $this->formatRow($account)),
        ]);
    }

    private function storeQrImage(UploadedFile $file): string
    {
        $name = now()->format('Ymd_His').'_'.substr(md5((string) microtime(true)), 0, 8).'.'.$file->getClientOriginalExtension();

        return $file->storeAs(self::QR_DIRECTORY, $name, self::QR_DISK);
    }

    private function deleteQrImage(?string $path): void
    {
        if ($path !== null && $path !== '' && Storage::disk(self::QR_DISK)->exists($path)) {
            Storage::disk(self::QR_DISK)->delete($path);
        }
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $searchValue = $request->input('search.value');
        if ($searchValue === null || $searchValue === '') {
            return;
        }
        $term = '%'.addcslashes($searchValue, '%_\\').'%';
        $query->where(function (Builder $q) use ($term) {
            $q->where('account_name', 'like', $term)
                ->orWhere('account_number', 'like', $term);
        });
    }

    private function applyOrdering(Builder $query, Request $request): void
    {
        $order = $request->input('order.0');
        if (! $order || ! isset($order['column'], $order['dir'])) {
            $query->orderBy('account_name', 'asc');

            return;
        }
        $columns = ['id', 'account_name', 'account_number', 'is_active'];
        $columnIndex = (int) $order['column'];
        $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
        $column = $columns[$columnIndex] ?? 'account_name';
        $query->orderBy($column, $dir);
    }

    private function getPaginatedData(Builder $query, Request $request)
    {
        $start = $request->integer('start', 0);
        $length = min($request->integer('length', 10), 100);

        return $query->skip($start)->take($length)->get();
    }

    private function formatRow(PaymentAccount $account): array
    {
        return [
            'id' => $account->id,
            'account_name' => e($account->account_name),
            'account_number' => e($account->account_number),
            'qr_image_path' => $account->qr_image_path,
            'has_qr' => ! empty($account->qr_image_path),
            'is_active' => $account->is_active,
            'actions' => [
                'id' => $account->id,
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'is_active' => $account->is_active,
            ],
        ];
    }
}
