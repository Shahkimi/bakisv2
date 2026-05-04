<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class MemberReceiptController extends Controller
{
    public function download(Member $member): Response
    {
        $member->load(['jabatan', 'jawatan', 'memberStatus', 'payments.yuran']);

        $pdf = Pdf::loadView('admin.members.receipt-pdf', compact('member'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        $filename = 'resit-keahlian-'.($member->no_ahli ?? $member->id).'.pdf';

        return $pdf->download($filename);
    }
}
