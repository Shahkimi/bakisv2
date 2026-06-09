<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

final class DashboardService
{
    /**
     * @return array{aktif: int, tidak_aktif: int, meninggal: int, total: int}
     */
    public function getStatusCounts(int $currentYear): array
    {
        $excludedStatusCodes = ['meninggal', 'pending'];
        $activePaymentQuery = $this->activePaymentConstraint($currentYear);

        $meninggalCount = Member::whereHas('memberStatus', fn ($q) => $q->where('code', 'meninggal'))->count();

        $aktifCount = Member::whereHas('memberStatus', fn ($q) => $q->whereNotIn('code', $excludedStatusCodes))
            ->whereHas('payments', $activePaymentQuery)
            ->count();

        $tidakAktifCount = Member::whereHas('memberStatus', fn ($q) => $q->whereNotIn('code', $excludedStatusCodes))
            ->whereDoesntHave('payments', $activePaymentQuery)
            ->count();

        return [
            'aktif' => $aktifCount,
            'tidak_aktif' => $tidakAktifCount,
            'meninggal' => $meninggalCount,
            'total' => $aktifCount + $tidakAktifCount + $meninggalCount,
        ];
    }

    /**
     * @return array<int, array{nama: string, no_kp: string|null, last_payment: int|null}>
     */
    public function getTidakAktifMembers(?string $search = null, int $limit = 5): array
    {
        $currentYear = (int) date('Y');
        $search = $search !== null ? trim($search) : '';
        $digits = $search !== '' ? (preg_replace('/\D/', '', $search) ?: '') : '';

        $members = Member::query()
            ->select(['id', 'nama', 'no_kp'])
            ->whereHas('memberStatus', fn ($q) => $q->whereNotIn('code', ['meninggal', 'pending']))
            ->whereDoesntHave('payments', $this->activePaymentConstraint($currentYear))
            ->with(['payments' => function (Relation $q): void {
                $q->where('status', 'approved')
                    ->select(['id', 'member_id', 'tahun_bayar', 'tahun_mula', 'tahun_tamat'])
                    ->orderByDesc('tahun_tamat')
                    ->orderByDesc('tahun_bayar')
                    ->limit(1);
            }])
            ->when($search !== '', function (Builder $query) use ($search, $digits): void {
                $query->where(function (Builder $q) use ($search, $digits): void {
                    $q->where('nama', 'like', '%'.$search.'%');

                    if ($digits !== '') {
                        $q->orWhere('no_kp', 'like', '%'.$digits.'%');
                    }
                });
            })
            ->orderBy('nama')
            ->limit($limit)
            ->get();

        return $members->map(function (Member $member): array {
            $lastPayment = $member->payments->first();

            return [
                'nama' => $member->nama,
                'no_kp' => $member->no_kp,
                'last_payment' => $lastPayment?->tahun_tamat
                    ?? $lastPayment?->tahun_mula
                    ?? $lastPayment?->tahun_bayar,
            ];
        })->all();
    }

    /**
     * @return Closure(Builder): void
     */
    private function activePaymentConstraint(int $currentYear): Closure
    {
        $graceThreshold = $currentYear - Member::GRACE_YEARS;

        return function (Builder $q) use ($currentYear, $graceThreshold): void {
            $q->where('status', 'approved')
                ->where('tahun_mula', '<=', $currentYear)
                ->where(function (Builder $q2) use ($graceThreshold): void {
                    $q2->whereNull('tahun_tamat')
                        ->orWhere('tahun_tamat', '>=', $graceThreshold);
                });
        };
    }
}
