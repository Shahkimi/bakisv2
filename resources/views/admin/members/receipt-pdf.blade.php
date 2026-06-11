<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    @php
        $isSinglePayment = isset($payment);
        if (!$member->relationLoaded('payments')) { $member->load(['payments.yuran']); }
        $approvedPayments = $member->payments->where('status', \App\Models\Payment::STATUS_APPROVED)->values();
        if ($isSinglePayment) $approvedPayments = collect([$payment]);
        $totalPaid       = $approvedPayments->sum(fn($p) => (float)($p->jumlah ?? 0));
        $receiptYear     = $isSinglePayment ? (int)$payment->tahun_bayar : (int)now()->year;
        $receiptNoSuffix = str_pad((string)($isSinglePayment ? $payment->id : $member->id), 6, '0', STR_PAD_LEFT);
        $receiptNo       = 'RCP-'.$receiptYear.'-'.$receiptNoSuffix;
        $issuedAt        = ($isSinglePayment && $payment->approved_at) ? $payment->approved_at : now();
        $statusName      = $member->memberStatus?->name ?? 'Tidak Aktif';
        $isActive        = str_contains(strtolower($statusName), 'aktif');
        $logoDataUri     = $logoDataUri ?? null;
        $paymentType     = $isSinglePayment
            ? match($payment->jenis ?? '') { 'pendaftaran_baru'=>'Pendaftaran Baru','pembaharuan'=>'Pembaharuan',default=>'Keahlian' }
            : 'Keahlian';
    @endphp
    <title>Resit Rasmi - {{ $receiptNo }}</title>
    <style>
        @page { size: A4 portrait; margin: 14mm 16mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            color: #475569;
            background: #ffffff;
            line-height: 1.5;
        }

        /* ── SEPARATORS ── */
        .black-sep { border: none; border-top: 1px solid #000000; margin: 0 0 18px; }

        /* ── PASTEL ACCENT + HEADER ── */
        .gold-rule { height: 4px; background: #c7d2fe; border-radius: 999px; font-size: 1px; line-height: 1px; }

        .header-card { width: 100%; border-collapse: separate; background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 16px; margin-top: 9px; margin-bottom: 18px; }
        .header-inner { padding: 22px 26px; }
        .header-left-cell { vertical-align: middle; width: 60%; }
        .header-right-cell { vertical-align: top; text-align: right; width: 40%; }

        .logo-plate { background: #ffffff; width: 56px; height: 56px; border-radius: 13px; text-align: center; vertical-align: middle; border: 1px solid #e0e7ff; }
        .logo-box { background-color: #4f46e5; color: #ffffff; font-size: 24px; font-weight: bold; width: 56px; height: 56px; text-align: center; vertical-align: middle; border-radius: 13px; }
        .org-logo { display: block; width: 50px; height: 50px; object-fit: contain; margin: 0 auto; }

        .brand-badge {
            display: inline-block; padding: 4px 12px;
            background: #e0e7ff; color: #3730a3;
            border: 1px solid #c7d2fe;
            border-radius: 999px; font-size: 9.5px; font-weight: bold;
            letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 8px;
        }
        .brand-title { margin: 0; font-size: 19px; font-weight: bold; color: #3730a3; line-height: 1.2; letter-spacing: 0.3px; }
        .brand-subtitle { margin: 4px 0 0; color: #64748b; font-size: 9.5px; letter-spacing: 0.3px; }

        .receipt-label { font-size: 30px; font-weight: bold; color: #3730a3; line-height: 1; margin: 0; letter-spacing: 3px; }
        .receipt-no { margin: 9px 0 0; font-size: 12px; color: #4f46e5; font-weight: bold; letter-spacing: 0.5px; }
        .receipt-date { margin: 4px 0 0; font-size: 9.5px; color: #64748b; }

        /* ── STATUS BANNER ── */
        .status-banner { width: 100%; border-collapse: separate; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; margin-bottom: 20px; }
        .status-accent { width: 5px; background: #6ee7b7; border-radius: 12px 0 0 12px; font-size: 1px; line-height: 1px; }
        .status-pad { padding: 13px 18px; }
        .status-title { margin: 0; font-size: 12.5px; font-weight: bold; color: #047857; }
        .status-desc { margin: 3px 0 0; font-size: 10.5px; color: #059669; }
        .lunas-pill { display: inline-block; padding: 5px 14px; background: #d1fae5; color: #047857; border: 1px solid #6ee7b7; border-radius: 999px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

        /* ── SECTION HEADER ── */
        .section-header { margin-bottom: 11px; }
        .section-tick { width: 4px; background: #a5b4fc; border-radius: 999px; font-size: 1px; line-height: 1px; }
        .section-title { margin: 0; font-size: 10.5px; text-transform: uppercase; letter-spacing: 1.6px; font-weight: bold; color: #3730a3; padding-left: 10px; }
        .aktif-badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.6px; }
        .aktif-badge-on  { background: #d1fae5; border: 1px solid #6ee7b7; color: #047857; }
        .aktif-badge-off { background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; }

        /* ── INFO GRID ── */
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid #e0e7ff; border-radius: 12px; overflow: hidden; }
        .info-cell { width: 50%; vertical-align: top; padding: 12px 16px; border: 1px solid #eef2ff; background: #ffffff; }
        .info-cell-alt { background: #fafbff; }
        .info-label { margin: 0 0 5px; font-size: 8.5px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: bold; }
        .info-value { margin: 0; font-size: 13px; font-weight: bold; color: #312e81; }
        .info-value-normal { margin: 0; font-size: 11.5px; color: #334155; font-weight: 600; }
        .info-value-indigo { margin: 0; font-size: 13px; font-weight: bold; color: #4f46e5; letter-spacing: 0.5px; }

        /* ── PAYMENT TABLE ── */
        .payment-table { width: 100%; border-collapse: collapse; margin-bottom: 0; border-radius: 12px; overflow: hidden; border: 1px solid #e0e7ff; }
        .payment-thead th { background: #e0e7ff; color: #3730a3; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.9px; padding: 11px 14px; text-align: left; font-weight: bold; }
        .payment-thead th.right { text-align: right; }
        .payment-thead th.center { text-align: center; }
        .payment-tbody td { padding: 12px 14px; border-bottom: 1px solid #eef2ff; vertical-align: top; font-size: 11.5px; }
        .payment-tbody tr.alt td { background: #fafbff; }
        .payment-item-title { font-weight: bold; color: #312e81; margin: 0; font-size: 12px; }
        .payment-item-sub { color: #94a3b8; font-size: 9.5px; margin: 3px 0 0; }
        .td-center { text-align: center; color: #64748b; }
        .td-right { text-align: right; font-weight: bold; color: #312e81; }

        /* ── TOTAL PANEL ── */
        .total-table { width: 100%; border-collapse: collapse; margin-top: 12px; margin-bottom: 22px; }
        .total-panel { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 14px; }
        .total-accent { width: 5px; background: #93c5fd; border-radius: 14px 0 0 14px; font-size: 1px; line-height: 1px; }
        .total-label-cell { padding: 16px 20px; vertical-align: middle; }
        .total-label { color: #6366f1; font-size: 10px; text-transform: uppercase; letter-spacing: 1.4px; font-weight: bold; }
        .total-sub { color: #94a3b8; font-size: 8.5px; margin-top: 2px; }
        .total-amount-cell { padding: 16px 20px; text-align: right; vertical-align: middle; border-left: 1px solid #c7d2fe; }
        .total-amount { color: #3730a3; font-size: 23px; font-weight: bold; letter-spacing: 0.5px; }

        /* ── PAYMENT METHOD ── */
        .method-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .method-cell { width: 24%; vertical-align: top; padding: 12px 14px; border: 1px solid #e0e7ff; background: #fafbff; border-radius: 10px; }
        .method-label { margin: 0 0 5px; font-size: 8.5px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: bold; }
        .method-value { margin: 0; font-size: 11.5px; font-weight: bold; color: #312e81; }
        .dibayar-pill { display: inline-block; padding: 4px 12px; background: #d1fae5; border: 1px solid #6ee7b7; color: #047857; border-radius: 999px; font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── NOTES ── */
        .notes-box { border: 1px solid #e0e7ff; border-left: 4px solid #a5b4fc; border-radius: 10px; padding: 15px 18px; margin-bottom: 22px; background: #f5f7ff; }
        .notes-title { margin: 0 0 8px; font-size: 10.5px; font-weight: bold; color: #3730a3; text-transform: uppercase; letter-spacing: 0.8px; }
        .notes-list { margin: 0; padding-left: 16px; color: #64748b; font-size: 10.5px; line-height: 1.8; }

        /* ── FOOTER ── */
        .footer-divider { border: none; border-top: 1px dashed #c7d2fe; margin-bottom: 14px; }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-left { vertical-align: bottom; width: 60%; }
        .footer-right { vertical-align: bottom; text-align: right; width: 40%; }
        .footer-brand { margin: 0; font-size: 11px; font-weight: bold; color: #3730a3; }
        .footer-sub { margin: 4px 0 0; font-size: 9px; color: #94a3b8; }
        .footer-verify { margin: 6px 0 0; font-size: 8.5px; color: #c7d2fe; letter-spacing: 0.4px; }
        .footer-signed { margin: 0; font-size: 9.5px; color: #94a3b8; }
        .sign-line { border-top: 1px solid #c7d2fe; padding-top: 5px; width: 150px; margin-left: auto; }
        .footer-signer { margin: 0; font-size: 10.5px; font-weight: bold; color: #4f46e5; }
        .footer-signer-sub { margin: 1px 0 0; font-size: 8.5px; color: #94a3b8; }
    </style>
</head>
<body>

    {{-- ── PASTEL ACCENT ── --}}
    <table width="100%" cellpadding="0" cellspacing="0"><tr><td class="gold-rule">&nbsp;</td></tr></table>

    {{-- ── HEADER ── --}}
    <table class="header-card" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header-inner">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header-left-cell">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle; padding-right:14px;">
                                        @if($logoDataUri)
                                            <table class="logo-plate" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle" style="height:56px;"><img src="{{ $logoDataUri }}" alt="Logo" class="org-logo" /></td></tr></table>
                                        @else
                                            <table cellpadding="0" cellspacing="0"><tr><td class="logo-box" width="56" height="56" align="center" valign="middle">B</td></tr></table>
                                        @endif
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <div class="brand-badge">Resit Rasmi</div>
                                        <h1 class="brand-title">BAKIS</h1>
                                        <p class="brand-subtitle">Sistem Pengurusan Keahlian</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="header-right-cell">
                            <p class="receipt-label">RESIT</p>
                            <p class="receipt-no"># {{ $receiptNo }}</p>
                            <p class="receipt-date">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── HEADER SEPARATOR ── --}}
    <hr class="black-sep">

    {{-- ── STATUS BANNER ── --}}
    <table class="status-banner" cellpadding="0" cellspacing="0">
        <tr>
            <td class="status-accent">&nbsp;</td>
            <td class="status-pad">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align:middle;">
                            @if($isSinglePayment)
                                <p class="status-title">Bayaran diterima &amp; disahkan</p>
                                <p class="status-desc">Resit rasmi bagi pembayaran yuran keahlian &mdash; {{ $paymentType }}</p>
                            @else
                                <p class="status-title">Ringkasan pembayaran disahkan</p>
                                <p class="status-desc">Status keahlian: {{ $statusName }} &mdash; {{ $approvedPayments->count() }} rekod pembayaran disahkan</p>
                            @endif
                        </td>
                        <td style="vertical-align:middle; text-align:right; width:90px;">
                            <span class="lunas-pill">Lunas</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── MEMBER INFO ── --}}
    <table class="section-header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align:middle;">
                <table cellpadding="0" cellspacing="0"><tr>
                    <td class="section-tick" height="13">&nbsp;</td>
                    <td><p class="section-title">Maklumat Ahli &amp; Pengebilan</p></td>
                </tr></table>
            </td>
            <td style="text-align:right; vertical-align:middle;">
                <span class="aktif-badge {{ $isActive ? 'aktif-badge-on' : 'aktif-badge-off' }}">{{ $statusName }}</span>
            </td>
        </tr>
    </table>

    <table class="info-grid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-cell">
                <p class="info-label">Nama Penuh Ahli</p>
                <p class="info-value">{{ $member->nama }}</p>
            </td>
            <td class="info-cell info-cell-alt">
                <p class="info-label">No. Ahli</p>
                <p class="info-value-indigo">{{ $member->no_ahli ?? '—' }}</p>
            </td>
        </tr>
        <tr>
            <td class="info-cell">
                <p class="info-label">E-Mel</p>
                <p class="info-value-normal">{{ $member->email ?? '—' }}</p>
            </td>
            <td class="info-cell info-cell-alt">
                <p class="info-label">No. Telefon / HP</p>
                <p class="info-value-normal">{{ $member->no_hp ?? $member->no_tel ?? '—' }}</p>
            </td>
        </tr>
        <tr>
            <td class="info-cell">
                <p class="info-label">No. Kad Pengenalan</p>
                <p class="info-value-normal">{{ $member->no_kp ?? '—' }}</p>
            </td>
            <td class="info-cell info-cell-alt">
                <p class="info-label">Tarikh Daftar</p>
                <p class="info-value-normal">{{ $member->created_at ? $member->created_at->format('d M Y') : '—' }}</p>
            </td>
        </tr>
    </table>

    {{-- ── SECTION SEPARATOR ── --}}
    <hr class="black-sep">

    {{-- ── PAYMENT DETAILS ── --}}
    <table class="section-header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align:middle;">
                <table cellpadding="0" cellspacing="0"><tr>
                    <td class="section-tick" height="13">&nbsp;</td>
                    <td><p class="section-title">Butiran Pembayaran</p></td>
                </tr></table>
            </td>
        </tr>
    </table>

    @if($approvedPayments->isEmpty())
        <div class="notes-box" style="border-left-color:#fca5a5; background:#fef7f7; margin-bottom:20px;">Tiada rekod pembayaran yang telah disahkan.</div>
    @else
    <table class="payment-table" cellpadding="0" cellspacing="0">
        <thead class="payment-thead">
            <tr>
                <th style="width:6%">#</th>
                <th>Perihal</th>
                <th class="center" style="width:8%">Ktt</th>
                <th class="right" style="width:18%">Harga (RM)</th>
                <th class="right" style="width:18%">Amaun (RM)</th>
            </tr>
        </thead>
        <tbody class="payment-tbody">
            @foreach($approvedPayments as $i => $p)
                @php
                    $start     = $p->tahun_mula ?? $p->tahun_bayar;
                    $end       = $p->tahun_tamat ?? $p->tahun_bayar;
                    $coverage  = ($start == $end) ? (string)$start : $start.' &ndash; '.$end;
                    $lineTotal = (float)($p->jumlah ?? 0);
                    $typeLabel = match($p->jenis ?? '') {
                        'pendaftaran_baru' => 'Yuran Pendaftaran Baru',
                        'pembaharuan'      => 'Yuran Pembaharuan',
                        default            => 'Yuran Keahlian',
                    };
                @endphp
                <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                    <td style="color:#a5b4fc; font-weight:bold;">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <p class="payment-item-title">{{ $typeLabel }}</p>
                        <p class="payment-item-sub">Liputan tahun: {!! $coverage !!}@if($p->yuran?->jenis_yuran) &middot; {{ $p->yuran->jenis_yuran }}@endif</p>
                    </td>
                    <td class="td-center">1</td>
                    <td class="td-right" style="color:#64748b;">{{ number_format($lineTotal, 2) }}</td>
                    <td class="td-right">{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">
                <table class="total-panel" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="total-accent">&nbsp;</td>
                        <td class="total-label-cell">
                            <div class="total-label">Jumlah Dibayar</div>
                            <div class="total-sub">{{ $approvedPayments->count() }} item &middot; {{ $receiptYear }}</div>
                        </td>
                        <td class="total-amount-cell">
                            <span class="total-amount">RM {{ number_format($totalPaid, 2) }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── PAYMENT METHOD ── --}}
    @if($isSinglePayment)
    <table class="section-header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align:middle;">
                <table cellpadding="0" cellspacing="0"><tr>
                    <td class="section-tick" height="13">&nbsp;</td>
                    <td><p class="section-title">Kaedah Pembayaran</p></td>
                </tr></table>
            </td>
        </tr>
    </table>

    <table class="method-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="method-cell">
                <p class="method-label">Kaedah</p>
                <p class="method-value">Sistem BAKIS</p>
            </td>
            <td style="width:1.3%;"></td>
            <td class="method-cell">
                <p class="method-label">Rujukan / No. Resit</p>
                <p class="method-value">{{ $payment->no_resit_sistem ?? '—' }}</p>
            </td>
            <td style="width:1.3%;"></td>
            <td class="method-cell">
                <p class="method-label">Tarikh Kelulusan</p>
                <p class="method-value">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '—' }}</p>
            </td>
            <td style="width:1.3%;"></td>
            <td class="method-cell" style="background:#e0f2fe; border-color:#bae6fd;">
                <p class="method-label">Status</p>
                <span class="dibayar-pill">Dibayar</span>
            </td>
        </tr>
    </table>
    @endif
    @endif

    {{-- ── NOTES ── --}}
    <div class="notes-box">
        <p class="notes-title">Nota Penting</p>
        <ol class="notes-list">
            <li>Resit ini dijana komputer dan sah tanpa tandatangan fizikal.</li>
            <li>Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.</li>
            <li>Sila simpan resit ini untuk rujukan dan rekod anda.</li>
            <li>Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.</li>
        </ol>
    </div>

    {{-- ── FOOTER ── --}}
    <hr class="footer-divider">
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="footer-left">
                <p class="footer-brand">BAKIS — Sistem Pengurusan Keahlian</p>
                <p class="footer-sub">Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone', 'UTC') }})</p>
                <p class="footer-verify">ID Pengesahan: {{ $receiptNo }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
