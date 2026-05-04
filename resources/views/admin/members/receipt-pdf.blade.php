<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Resit Keahlian — {{ $member->no_ahli ?? 'N/A' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 16mm 12mm 16mm;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background: #ffffff;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            position: relative;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 0;
            padding-bottom: 8px;
            border-bottom: 2.5px solid #1a3c5e;
        }

        .header-table td { vertical-align: top; padding-bottom: 6px; }

        .brand-inner-table { border-collapse: collapse; width: auto; }
        .brand-inner-table td { vertical-align: middle; padding: 0; }

        .brand-icon {
            width: 46px;
            height: 46px;
            background-color: #1a3c5e;
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            border-radius: 8px;
            padding-right: 0;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a3c5e;
            letter-spacing: 0.5px;
            padding-left: 10px;
            vertical-align: middle;
        }

        .brand-tagline {
            font-size: 10px;
            color: #7a8fa6;
            margin-top: 4px;
            margin-left: 56px;
        }

        .brand-address {
            font-size: 9px;
            color: #7a8fa6;
            margin-top: 2px;
            margin-left: 56px;
            line-height: 1.5;
        }

        .receipt-badge { text-align: right; }
        .receipt-title { font-size: 26px; font-weight: 800; color: #1a3c5e; letter-spacing: 1px; text-transform: uppercase; }
        .receipt-no { font-size: 11px; color: #2e7dd1; font-weight: 600; margin-top: 4px; }
        .receipt-date { font-size: 10px; color: #7a8fa6; margin-top: 2px; }

        /* ── Status Banner ── */
        .status-banner {
            margin-top: 10px;
            margin-bottom: 12px;
            background-color: #e8f5e9;
            border-left: 5px solid #27ae60;
            border-radius: 6px;
            padding: 8px 14px;
        }

        .status-banner-inner { border-collapse: collapse; width: 100%; }
        .status-dot-cell { width: 18px; vertical-align: middle; padding-right: 8px; }
        .status-dot {
            width: 10px;
            height: 10px;
            background-color: #27ae60;
            border-radius: 50%;
            display: inline-block;
        }
        .status-text-cell { vertical-align: middle; }
        .status-text { font-size: 11px; color: #1e7e34; font-weight: 700; }
        .status-sub  { font-size: 10px; color: #555555; margin-top: 2px; }

        /* ── Section Labels ── */
        .section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.1px;
            color: #2e7dd1;
            text-transform: uppercase;
            margin: 12px 0 6px 0;
        }

        /* ── Info Grid ── */
        .info-grid-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 10px;
        }

        .info-grid-table td { width: 50%; vertical-align: top; padding: 3px; }

        .info-box {
            background-color: #f7f9fc;
            border: 1px solid #e4eaf2;
            border-radius: 8px;
            padding: 8px 12px;
            min-height: 44px;
        }

        .info-box .label {
            font-size: 9px;
            color: #8a9ab0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-box .value {
            font-size: 12px;
            color: #1a3c5e;
            font-weight: 600;
            margin-top: 2px;
        }

        .info-box .value.highlight { color: #2e7dd1; }
        .info-box .value.green     { color: #27ae60; }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .items-table thead tr { background-color: #1a3c5e; color: #ffffff; }

        .items-table thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .items-table thead th:last-child { text-align: right; }
        .items-table tbody tr { border-bottom: 1px solid #eef2f7; }
        .items-table tbody td { padding: 9px 12px; vertical-align: top; color: #333333; }
        .items-table tbody td:last-child { text-align: right; font-weight: 600; color: #1a3c5e; }
        .items-table tbody tr:nth-child(even) { background-color: #f7f9fc; }

        .item-name { font-weight: 700; color: #1a3c5e; }
        .item-desc { font-size: 9px; color: #8a9ab0; margin-top: 2px; }

        /* ── Totals ── */
        .totals-outer {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .totals-table {
            width: 240px;
            border-collapse: collapse;
            font-size: 11px;
            margin-left: auto;
        }

        .totals-table td {
            padding: 4px 10px;
            color: #555555;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: #1a3c5e;
        }

        .totals-table .grand-total td {
            font-size: 14px;
            font-weight: 800;
            color: #1a3c5e;
            border-top: 2px solid #1a3c5e;
            padding-top: 8px;
        }

        .totals-table .grand-total td:last-child { color: #2e7dd1; }

        /* ── Payment Info ── */
        .payment-info-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 10px;
        }

        .payment-info-table td { width: 25%; vertical-align: top; padding: 3px; }

        .pay-box {
            background-color: #f7f9fc;
            border: 1px solid #e4eaf2;
            border-radius: 8px;
            padding: 8px 12px;
            min-height: 48px;
        }

        .pay-box .label { font-size: 9px; color: #8a9ab0; text-transform: uppercase; letter-spacing: 0.8px; }
        .pay-box .value { font-size: 11px; color: #1a3c5e; font-weight: 600; margin-top: 3px; word-wrap: break-word; }
        .pay-box.paid-box { background-color: #e8f5e9; border-color: #a5d6a7; }
        .pay-box.paid-box .value { color: #27ae60; font-weight: 700; }

        /* ── Notes ── */
        .notes-box {
            background-color: #fffbf0;
            border: 1px solid #ffe58f;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 10px;
            color: #7a5c00;
            line-height: 1.6;
        }

        .notes-box strong { display: block; margin-bottom: 4px; font-size: 10px; }

        /* ── Footer ── */
        .footer-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1.5px dashed #c5d3e0;
        }

        .footer-table td { vertical-align: bottom; font-size: 9px; color: #8a9ab0; line-height: 1.7; }
        .footer-right { text-align: right; }

        .sig-wrap { width: 100%; text-align: right; }

        .sig-line {
            display: inline-block;
            border-top: 1px solid #c5d3e0;
            margin-top: 18px;
            padding-top: 4px;
            width: 130px;
            text-align: center;
            font-size: 9px;
            color: #8a9ab0;
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
    $totalPaid     = $approvedPayments->sum(fn ($p) => (float) ($p->jumlah ?? 0));
    $receiptYear   = $isSinglePayment ? (int) $payment->tahun_bayar : (int) now()->year;
    $receiptNoSuffix = $isSinglePayment
        ? str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT)
        : str_pad((string) $member->id, 6, '0', STR_PAD_LEFT);
    $receiptNo     = 'RCP-' . $receiptYear . '-' . $receiptNoSuffix;
    $issuedAt      = ($isSinglePayment && $payment->approved_at) ? $payment->approved_at : now();
    $statusName    = $member->memberStatus?->name ?? 'Tidak Aktif';
@endphp

<div class="page">

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            <td width="62%">
                <table class="brand-inner-table">
                    <tr>
                        <td class="brand-icon" width="46" height="46">B</td>
                        <td class="brand-name">BAKIS</td>
                    </tr>
                </table>
                <div class="brand-tagline">Sistem Pengurusan Keahlian</div>
                <div class="brand-address">Dokumen resit dijana secara elektronik. Untuk pertanyaan, hubungi pentadbir sistem.</div>
            </td>
            <td width="38%" class="receipt-badge">
                <div class="receipt-title">Resit</div>
                <div class="receipt-no"># {{ $receiptNo }}</div>
                <div class="receipt-date">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- ── STATUS BANNER ── --}}
    <div class="status-banner">
        <table class="status-banner-inner">
            <tr>
                <td class="status-dot-cell">
                    <div class="status-dot"></div>
                </td>
                <td class="status-text-cell">
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
    </div>

    {{-- ── MEMBER INFO ── --}}
    <div class="section-label">Maklumat Ahli &amp; Pengebilan</div>
    <table class="info-grid-table">
        <tr>
            <td>
                <div class="info-box">
                    <div class="label">Nama Ahli</div>
                    <div class="value">{{ $member->nama }}</div>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <div class="label">No. Ahli</div>
                    <div class="value highlight">{{ $member->no_ahli ?? '—' }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="info-box">
                    <div class="label">E-mel</div>
                    <div class="value">{{ $member->email ?? '—' }}</div>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <div class="label">No. Telefon / HP</div>
                    <div class="value">{{ $member->no_hp ?? $member->no_tel ?? '—' }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="info-box">
                    <div class="label">No. KP</div>
                    <div class="value">{{ $member->no_kp ?? '—' }}</div>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <div class="label">Status Keahlian</div>
                    <div class="value green">&#9679; {{ $statusName }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ── PAYMENT DETAILS ── --}}
    <div class="section-label">Butiran Pembayaran</div>
    @if($approvedPayments->isEmpty())
        <div class="notes-box">
            <strong>Nota:</strong> Tiada rekod pembayaran yang telah disahkan.
        </div>
    @else
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:6%">#</th>
                    <th style="width:48%">Perihal</th>
                    <th style="width:8%">Ktt</th>
                    <th style="width:19%">Harga (RM)</th>
                    <th style="width:19%">Amaun (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedPayments as $i => $p)
                    @php
                        $start     = $p->tahun_mula ?? $p->tahun_bayar;
                        $end       = $p->tahun_tamat ?? $p->tahun_bayar;
                        $coverage  = ($start == $end) ? (string) $start : $start . ' – ' . $end;
                        $lineTotal = (float) ($p->jumlah ?? 0);
                        $typeLabel = match ($p->jenis ?? '') {
                            'pendaftaran_baru' => 'Yuran Pendaftaran Baru',
                            'pembaharuan'      => 'Yuran Pembaharuan',
                            default            => 'Yuran Keahlian',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $typeLabel }}</div>
                            <div class="item-desc">Liputan tahun: {{ $coverage }}@if($p->yuran?->jenis_yuran) &nbsp;&#183;&nbsp; {{ $p->yuran->jenis_yuran }}@endif</div>
                        </td>
                        <td>1</td>
                        <td>{{ number_format($lineTotal, 2) }}</td>
                        <td>{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── TOTALS ── --}}
        <table class="totals-outer">
            <tr>
                <td align="right">
                    <table class="totals-table">
                        <tr class="grand-total">
                            <td>JUMLAH DIBAYAR</td>
                            <td>RM {{ number_format($totalPaid, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- ── PAYMENT METHOD ── --}}
        @if($isSinglePayment)
            <div class="section-label">Kaedah Pembayaran</div>
            <table class="payment-info-table">
                <tr>
                    <td>
                        <div class="pay-box">
                            <div class="label">Kaedah</div>
                            <div class="value">Pembayaran melalui sistem BAKIS</div>
                        </div>
                    </td>
                    <td>
                        <div class="pay-box">
                            <div class="label">Rujukan / No. Resit</div>
                            <div class="value">{{ $payment->no_resit_sistem ?? '—' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="pay-box">
                            <div class="label">Tarikh Kelulusan</div>
                            <div class="value">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '—' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="pay-box paid-box">
                            <div class="label">Status</div>
                            <div class="value">&#10004; DIBAYAR</div>
                        </div>
                    </td>
                </tr>
            </table>
        @endif
    @endif

    {{-- ── NOTES ── --}}
    <div class="notes-box">
        <strong>&#128204; Nota penting:</strong>
        1. Resit ini dijana komputer dan sah tanpa tandatangan fizikal.<br/>
        2. Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.<br/>
        3. Sila simpan resit ini untuk rujukan dan rekod anda.<br/>
        4. Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.
    </div>

    {{-- ── FOOTER ── --}}
    <table class="footer-table">
        <tr>
            <td width="55%">
                <strong style="color:#1a3c5e;">BAKIS</strong> — Sistem Pengurusan Keahlian<br/>
                Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone', 'UTC') }})
            </td>
            <td width="45%" class="footer-right">
                <div>Disahkan oleh:</div>
                <div class="sig-wrap">
                    <div class="sig-line">Pentadbir Keahlian</div>
                </div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
