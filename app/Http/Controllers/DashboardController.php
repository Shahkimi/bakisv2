<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentYear = (int) date('Y');
        $graceThreshold = $currentYear - Member::GRACE_YEARS;

        $activePaymentQuery = fn ($q) => $q
            ->where('status', 'approved')
            ->where('tahun_mula', '<=', $currentYear)
            ->where(fn ($q2) => $q2->whereNull('tahun_tamat')->orWhere('tahun_tamat', '>=', $graceThreshold));

        $excludedStatusCodes = ['meninggal', 'pending'];

        $meninggalCount = Member::whereHas('memberStatus', fn ($q) => $q->where('code', 'meninggal'))->count();

        $aktifCount = Member::whereHas('memberStatus', fn ($q) => $q->whereNotIn('code', $excludedStatusCodes))
            ->whereHas('payments', $activePaymentQuery)
            ->count();

        $tidakAktifCount = Member::whereHas('memberStatus', fn ($q) => $q->whereNotIn('code', $excludedStatusCodes))
            ->whereDoesntHave('payments', $activePaymentQuery)
            ->count();

        $totalCount = $aktifCount + $tidakAktifCount + $meninggalCount;

        return view('dashboard', compact('aktifCount', 'tidakAktifCount', 'meninggalCount', 'totalCount', 'currentYear'));
    }
}
