<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Resit Keahlian &mdash; {{ $member->no_ahli ?? 'N/A' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 16mm 12mm 16mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background: #ffffff;
            line-height: 1.5;
        }

        /* ── Header ── */
        .header-sep {
            border: none;
            border-top: 2.5px solid #1a3c5e;
            margin: 0 0 10px 0;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background-color: #1a3c5e;
            color: #ffffff;
            font-size: 22px; font-weight: 700;
            text-align: center; vertical-align: middle;
            border-radius: 8px;
        }
        .brand-name {
            font-size: 20px; font-weight: 700;
            color: #1a3c5e; letter-spacing: 0.5px;
            vertical-align: middle; padding-left: 10px;
        }
        .brand-tagline { font-size: 10px; color: #7a8fa6; }
        .brand-addr    { font-size: 9px;  color: #7a8fa6; line-height: 1.5; }
        .receipt-title { font-size: 26px; font-weight: 800; color: #1a3c5e; letter-spacing: 1px; text-transform: uppercase; text-align: right; }
        .receipt-no    { font-size: 11px; color: #2e7dd1; font-weight: 600; text-align: right; }
        .receipt-date  { font-size: 10px; color: #7a8fa6; text-align: right; }

        /* ── Status Banner ── */
        .status-banner {
            background-color: #e8f5e9;
            border-left: 5px solid #27ae60;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 12px;
        }
        .status-dot {
            width: 10px; height: 10px;
            background-color: #27ae60;
            border-radius: 50%;
        }
        .status-text { font-size: 11px; color: #1e7e34; font-weight: 700; }
        .status-sub  { font-size: 10px; color: #555555; }

        /* ── Section Label ── */
        .section-label {
            font-size: 9px; font-weight: 700;
            letter-spacing: 1.1px; color: #2e7dd1;
            text-transform: uppercase;
            padding: 10px 0 5px 0;
        }

        /* ── Info / Pay boxes ── */
        .info-box {
            background-color: #f7f9fc;
            border: 1px solid #e4eaf2;
            border-radius: 6px;
            padding: 8px 10px;
        }
        .info-label { font-size: 9px; color: #8a9ab0; text-transform: uppercase; letter-spacing: 0.7px; }
        .info-value { font-size: 12px; color: #1a3c5e; font-weight: 600; padding-top: 2px; }
        .info-value-blue  { font-size: 12px; color: #2e7dd1; font-weight: 600; padding-top: 2px; }
        .info-value-green { font-size: 12px; color: #27ae60; font-weight: 600; padding-top: 2px; }

        .pay-box {
            background-color: #f7f9fc;
            border: 1px solid #e4eaf2;
            border-radius: 6px;
            padding: 8px 10px;
        }
        .pay-label { font-size: 8px; color: #8a9ab0; text-transform: uppercase; letter-spacing: 0.5px; }
        .pay-value { font-size: 10px; color: #1a3c5e; font-weight: 600; padding-top: 3px; word-wrap: break-word; }
        .pay-box-paid {
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            padding: 8px 10px;
        }
        .pay-value-paid { font-size: 10px; color: #27ae60; font-weight: 700; padding-top: 3px; }

        /* ── Items Table ── */
        .items-table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 4px; }
        .items-table thead tr   { background-color: #1a3c5e; color: #ffffff; }
        .items-table thead th   { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; }
        .items-table thead th.right { text-align: right; }
        .items-table tbody tr   { border-bottom: 1px solid #eef2f7; }
        .items-table tbody td   { padding: 9px 10px; vertical-align: top; color: #333333; }
        .items-table tbody td.right { text-align: right; font-weight: 600; color: #1a3c5e; }
        .items-table tbody tr.even  { background-color: #f7f9fc; }
        .item-name { font-weight: 700; color: #1a3c5e; }
        .item-desc { font-size: 9px; color: #8a9ab0; padding-top: 2px; }

        /* ── Totals ── */
        .totals-row td { padding: 4px 10px; color: #555555; }
        .totals-row td.tlabel { text-align: left; }
        .totals-row td.tamount { text-align: right; font-weight: 600; color: #1a3c5e; }
        .grand-row td {
            border-top: 2px solid #1a3c5e;
            padding: 8px 10px 4px 10px;
            font-size: 14px; font-weight: 800; color: #1a3c5e;
        }
        .grand-row td.tamount { color: #2e7dd1; text-align: right; }

        /* ── Notes ── */
        .notes-box {
            background-color: #fffbf0;
            border: 1px solid #ffe58f;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 10px; color: #7a5c00;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .notes-title { font-weight: 700; font-size: 10px; display: block; margin-bottom: 4px; }

        /* ── Footer ── */
        .footer-sep {
            border: none;
            border-top: 1.5px dashed #c5d3e0;
            margin: 14px 0 8px 0;
        }
        .footer-left  { font-size: 9px; color: #8a9ab0; line-height: 1.7; }
        .footer-right { font-size: 9px; color: #8a9ab0; text-align: right; }
        .sig-line {
            border-top: 1px solid #c5d3e0;
            margin-top: 18px; padding-top: 4px;
            width: 130px; text-align: center;
            font-size: 9px; color: #8a9ab0;
            float: right;
        }
    </style>
</head>
<body>
@php
    $isSinglePayment = isset($payment);
    if (! $member->relationLoaded('payments')) {
        $member->load(['payments.yuran']);
    }
    $approvedPayments = $member->payments->where('status', \App\Models\Payment::STATUS_APPROVED)->values();
    if ($isSinglePayment) {
        $approvedPayments = collect([$payment]);
    }
    $totalPaid       = $approvedPayments->sum(fn ($p) => (float) ($p->jumlah ?? 0));
    $receiptYear     = $isSinglePayment ? (int) $payment->tahun_bayar : (int) now()->year;
    $receiptNoSuffix = $isSinglePayment
        ? str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT)
        : str_pad((string) $member->id,  6, '0', STR_PAD_LEFT);
    $receiptNo       = 'RCP-' . $receiptYear . '-' . $receiptNoSuffix;
    $issuedAt        = ($isSinglePayment && $payment->approved_at) ? $payment->approved_at : now();
    $statusName      = $member->memberStatus?->name ?? 'Tidak Aktif';
@endphp

{{-- ═══════════════════════════════
     HEADER
═══════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2.5px solid #1a3c5e; margin-bottom: 10px;">
    <tr>
        <td width="62%" style="vertical-align: top; padding-bottom: 8px;">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class="brand-icon" width="46" height="46" align="center">B</td>
                    <td class="brand-name">BAKIS</td>
                </tr>
            </table>
            <div style="height: 4px;"></div>
            <div class="brand-tagline">Sistem Pengurusan Keahlian</div>
            <div class="brand-addr">Dokumen resit dijana secara elektronik. Untuk pertanyaan, hubungi pentadbir sistem.</div>
        </td>
        <td width="38%" style="vertical-align: top; padding-bottom: 8px;">
            <div class="receipt-title">RESIT</div>
            <div class="receipt-no"># {{ $receiptNo }}</div>
            <div class="receipt-date">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</div>
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════
     STATUS BANNER
═══════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#e8f5e9; border-left: 5px solid #27ae60; border-radius: 6px; margin-bottom: 12px;">
    <tr>
        <td style="padding: 8px 14px;">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="16" style="vertical-align: middle; padding-right: 8px;">
                        <table cellpadding="0" cellspacing="0" width="10" height="10">
                            <tr><td style="background-color: #27ae60; border-radius: 50%; width: 10px; height: 10px; font-size: 1px;">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="vertical-align: middle;">
                        @if($isSinglePayment)
                            <div class="status-text">&#10004; Bayaran diterima &amp; disahkan</div>
                            <div class="status-sub">Resit rasmi bagi pembayaran yuran keahlian ({{ match ($payment->jenis ?? '') {
                                'pendaftaran_baru' => 'Pendaftaran Baru',
                                'pembaharuan'      => 'Pembaharuan',
                                default            => 'Keahlian',
                            } }}).</div>
                        @else
                            <div class="status-text">&#10004; Ringkasan pembayaran disahkan</div>
                            <div class="status-sub">Status keahlian semasa: {{ $statusName }}. Jumlah rekod disahkan: {{ $approvedPayments->count() }}.</div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════
     MEMBER INFO
═══════════════════════════════ --}}
<div class="section-label">Maklumat Ahli &amp; Pengebilan</div>

{{-- Row 1: Nama | No. Ahli --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 5px;">
    <tr>
        <td width="50%" style="padding-right: 4px;">
            <div class="info-box">
                <div class="info-label">Nama Ahli</div>
                <div class="info-value">{{ $member->nama }}</div>
            </div>
        </td>
        <td width="50%" style="padding-left: 4px;">
            <div class="info-box">
                <div class="info-label">No. Ahli</div>
                <div class="info-value-blue">{{ $member->no_ahli ?? '&mdash;' }}</div>
            </div>
        </td>
    </tr>
</table>
{{-- Row 2: E-mel | Telefon --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 5px;">
    <tr>
        <td width="50%" style="padding-right: 4px;">
            <div class="info-box">
                <div class="info-label">E-mel</div>
                <div class="info-value">{{ $member->email ?? '&mdash;' }}</div>
            </div>
        </td>
        <td width="50%" style="padding-left: 4px;">
            <div class="info-box">
                <div class="info-label">No. Telefon / HP</div>
                <div class="info-value">{{ $member->no_hp ?? $member->no_tel ?? '&mdash;' }}</div>
            </div>
        </td>
    </tr>
</table>
{{-- Row 3: No. KP | Status --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
    <tr>
        <td width="50%" style="padding-right: 4px;">
            <div class="info-box">
                <div class="info-label">No. KP</div>
                <div class="info-value">{{ $member->no_kp ?? '&mdash;' }}</div>
            </div>
        </td>
        <td width="50%" style="padding-left: 4px;">
            <div class="info-box">
                <div class="info-label">Status Keahlian</div>
                <div class="info-value-green">&#9679; {{ $statusName }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════
     PAYMENT DETAILS
═══════════════════════════════ --}}
<div class="section-label">Butiran Pembayaran</div>

@if($approvedPayments->isEmpty())
    <div class="notes-box"><strong>Nota:</strong> Tiada rekod pembayaran yang telah disahkan.</div>
@else
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:49%;">Perihal</th>
                <th style="width:8%;">Ktt</th>
                <th style="width:19%;">Harga (RM)</th>
                <th class="right" style="width:19%;">Amaun (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedPayments as $i => $p)
                @php
                    $start     = $p->tahun_mula ?? $p->tahun_bayar;
                    $end       = $p->tahun_tamat ?? $p->tahun_bayar;
                    $coverage  = ($start == $end) ? (string) $start : $start . ' &ndash; ' . $end;
                    $lineTotal = (float) ($p->jumlah ?? 0);
                    $typeLabel = match ($p->jenis ?? '') {
                        'pendaftaran_baru' => 'Yuran Pendaftaran Baru',
                        'pembaharuan'      => 'Yuran Pembaharuan',
                        default            => 'Yuran Keahlian',
                    };
                @endphp
                <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $typeLabel }}</div>
                        <div class="item-desc">Liputan tahun: {!! $coverage !!}@if($p->yuran?->jenis_yuran) &nbsp;&middot;&nbsp; {{ $p->yuran->jenis_yuran }}@endif</div>
                    </td>
                    <td>1</td>
                    <td>{{ number_format($lineTotal, 2) }}</td>
                    <td class="right">{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 4px; margin-bottom: 10px;">
        <tr>
            <td width="55%"></td>
            <td width="45%">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr class="grand-row">
                        <td class="tlabel">JUMLAH DIBAYAR</td>
                        <td class="tamount">RM {{ number_format($totalPaid, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Payment Method --}}
    @if($isSinglePayment)
        <div class="section-label">Kaedah Pembayaran</div>
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
            <tr>
                <td width="25%" style="padding-right: 4px;">
                    <div class="pay-box">
                        <div class="pay-label">Kaedah</div>
                        <div class="pay-value">Pembayaran melalui sistem BAKIS</div>
                    </div>
                </td>
                <td width="25%" style="padding-left: 4px; padding-right: 4px;">
                    <div class="pay-box">
                        <div class="pay-label">Rujukan / No. Resit</div>
                        <div class="pay-value">{{ $payment->no_resit_sistem ?? '&mdash;' }}</div>
                    </div>
                </td>
                <td width="25%" style="padding-left: 4px; padding-right: 4px;">
                    <div class="pay-box">
                        <div class="pay-label">Tarikh Kelulusan</div>
                        <div class="pay-value">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '&mdash;' }}</div>
                    </div>
                </td>
                <td width="25%" style="padding-left: 4px;">
                    <div class="pay-box-paid">
                        <div class="pay-label">Status</div>
                        <div class="pay-value-paid">&#10004; DIBAYAR</div>
                    </div>
                </td>
            </tr>
        </table>
    @endif
@endif

{{-- ═══════════════════════════════
     NOTES
═══════════════════════════════ --}}
<div class="notes-box">
    <span class="notes-title">&#128204; Nota penting:</span>
    1. Resit ini dijana komputer dan sah tanpa tandatangan fizikal.<br/>
    2. Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.<br/>
    3. Sila simpan resit ini untuk rujukan dan rekod anda.<br/>
    4. Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.
</div>

{{-- ═══════════════════════════════
     FOOTER
═══════════════════════════════ --}}
<hr class="footer-sep" />
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="55%" class="footer-left" style="vertical-align: bottom;">
            <strong style="color:#1a3c5e;">BAKIS</strong> &mdash; Sistem Pengurusan Keahlian<br/>
            Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone', 'UTC') }})
        </td>
        <td width="45%" class="footer-right" style="vertical-align: bottom;">
            <div>Disahkan oleh:</div>
            <div style="margin-top: 18px; border-top: 1px solid #c5d3e0; padding-top: 4px; width: 130px; text-align: center; margin-left: auto; font-size: 9px; color: #8a9ab0;">Pentadbir Keahlian</div>
        </td>
    </tr>
</table>

</body>
</html>
