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
            color: #333;
            background: #fff;
            line-height: 1.5;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background-color: #1a3c5e;
            color: #fff;
            font-size: 22px; font-weight: 700;
            text-align: center; vertical-align: middle;
            border-radius: 8px;
        }
        .receipt-title { font-size: 26px; font-weight: 800; color: #1a3c5e; letter-spacing: 1px; text-transform: uppercase; }
        .receipt-no    { font-size: 11px; color: #2e7dd1; font-weight: 600; }
        .receipt-date  { font-size: 10px; color: #7a8fa6; }
        .status-text   { font-size: 11px; color: #1e7e34; font-weight: 700; }
        .status-sub    { font-size: 10px; color: #555; padding-top: 1px; }
        .section-label { font-size: 9px; font-weight: 700; letter-spacing: 1.1px; color: #2e7dd1; text-transform: uppercase; }
        .lbl  { font-size: 9px; color: #8a9ab0; text-transform: uppercase; letter-spacing: 0.7px; }
        .val  { font-size: 12px; color: #1a3c5e; font-weight: 600; padding-top: 2px; }
        .val-blue  { font-size: 12px; color: #2e7dd1; font-weight: 600; padding-top: 2px; }
        .val-green { font-size: 12px; color: #27ae60; font-weight: 600; padding-top: 2px; }
        .plbl  { font-size: 8px; color: #8a9ab0; text-transform: uppercase; letter-spacing: 0.5px; }
        .pval  { font-size: 10px; color: #1a3c5e; font-weight: 600; padding-top: 2px; word-wrap: break-word; }
        .pval-paid { font-size: 10px; color: #27ae60; font-weight: 700; padding-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .items-table thead tr { background-color: #1a3c5e; color: #fff; }
        .items-table thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; }
        .items-table tbody tr { border-bottom: 1px solid #eef2f7; }
        .items-table tbody tr.even { background-color: #f7f9fc; }
        .items-table tbody td { padding: 9px 10px; vertical-align: top; }
        .item-name { font-weight: 700; color: #1a3c5e; }
        .item-desc { font-size: 9px; color: #8a9ab0; padding-top: 2px; }
        .notes-box {
            background-color: #fffbf0;
            border: 1px solid #ffe58f;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 10px; color: #7a5c00; line-height: 1.6;
        }
        .footer-lft { font-size: 9px; color: #8a9ab0; line-height: 1.7; vertical-align: bottom; }
        .footer-rgt { font-size: 9px; color: #8a9ab0; text-align: right; vertical-align: bottom; }
    </style>
</head>
<body>
@php
    $isSinglePayment = isset($payment);
    if (!$member->relationLoaded('payments')) {
        $member->load(['payments.yuran']);
    }
    $approvedPayments = $member->payments->where('status', \App\Models\Payment::STATUS_APPROVED)->values();
    if ($isSinglePayment) {
        $approvedPayments = collect([$payment]);
    }
    $totalPaid       = $approvedPayments->sum(fn($p) => (float)($p->jumlah ?? 0));
    $receiptYear     = $isSinglePayment ? (int)$payment->tahun_bayar : (int)now()->year;
    $receiptNoSuffix = $isSinglePayment
        ? str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT)
        : str_pad((string)$member->id,  6, '0', STR_PAD_LEFT);
    $receiptNo   = 'RCP-' . $receiptYear . '-' . $receiptNoSuffix;
    $issuedAt    = ($isSinglePayment && $payment->approved_at) ? $payment->approved_at : now();
    $statusName  = $member->memberStatus?->name ?? 'Tidak Aktif';
@endphp

{{-- ═══════════════════════════════════
     HEADER
═══════════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" style="border-bottom:2.5px solid #1a3c5e; padding-bottom:8px; margin-bottom:10px;">
  <tr>
    <td width="60%" style="vertical-align:top;">
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td class="brand-icon" width="46" height="46" align="center" valign="middle">B</td>
          <td style="padding-left:10px; vertical-align:middle; font-size:20px; font-weight:700; color:#1a3c5e; letter-spacing:0.5px;">BAKIS</td>
        </tr>
      </table>
      <table cellpadding="0" cellspacing="0" style="margin-top:4px;">
        <tr><td style="font-size:10px; color:#7a8fa6; padding-left:56px;">Sistem Pengurusan Keahlian</td></tr>
        <tr><td style="font-size:9px; color:#7a8fa6; padding-left:56px;">Dokumen resit dijana secara elektronik. Untuk pertanyaan, hubungi pentadbir sistem.</td></tr>
      </table>
    </td>
    <td width="40%" style="vertical-align:top; text-align:right;">
      <div class="receipt-title">RESIT</div>
      <div class="receipt-no"># {{ $receiptNo }}</div>
      <div class="receipt-date">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</div>
    </td>
  </tr>
</table>

{{-- ═══════════════════════════════════
     STATUS BANNER
═══════════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#e8f5e9; border-left:5px solid #27ae60; border-radius:6px; margin-bottom:12px;">
  <tr>
    <td style="padding:9px 14px;">
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td width="18" style="vertical-align:middle;">
            <table cellpadding="0" cellspacing="0" width="10" height="10"><tr><td width="10" height="10" style="background-color:#27ae60; border-radius:5px; font-size:1px;">&nbsp;</td></tr></table>
          </td>
          <td style="vertical-align:middle; padding-left:8px;">
            @if($isSinglePayment)
              <div class="status-text">&#10004; Bayaran diterima &amp; disahkan</div>
              <div class="status-sub">Resit rasmi bagi pembayaran yuran keahlian ({{ match($payment->jenis ?? '') {
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

{{-- ═══════════════════════════════════
     MEMBER INFO — uses cellspacing=5 on outer wrapper
     so every cell gets natural gap between boxes
═══════════════════════════════════ --}}
<div class="section-label" style="margin-bottom:6px;">Maklumat Ahli &amp; Pengebilan</div>

<table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:10px;">
  <tr>
    <td width="50%" style="padding-right:5px; padding-bottom:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">Nama Ahli</div><div class="val">{{ $member->nama }}</div></td></tr>
      </table>
    </td>
    <td width="50%" style="padding-left:5px; padding-bottom:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">No. Ahli</div><div class="val-blue">{{ $member->no_ahli ?? '&mdash;' }}</div></td></tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="50%" style="padding-right:5px; padding-bottom:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">E-mel</div><div class="val">{{ $member->email ?? '&mdash;' }}</div></td></tr>
      </table>
    </td>
    <td width="50%" style="padding-left:5px; padding-bottom:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">No. Telefon / HP</div><div class="val">{{ $member->no_hp ?? $member->no_tel ?? '&mdash;' }}</div></td></tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="50%" style="padding-right:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">No. KP</div><div class="val">{{ $member->no_kp ?? '&mdash;' }}</div></td></tr>
      </table>
    </td>
    <td width="50%" style="padding-left:5px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
        <tr><td style="padding:8px 12px;"><div class="lbl">Status Keahlian</div><div class="val-green">&#9679; {{ $statusName }}</div></td></tr>
      </table>
    </td>
  </tr>
</table>

{{-- ═══════════════════════════════════
     PAYMENT DETAILS
═══════════════════════════════════ --}}
<div class="section-label" style="margin-bottom:6px;">Butiran Pembayaran</div>

@if($approvedPayments->isEmpty())
  <div class="notes-box" style="margin-bottom:10px;"><strong>Nota:</strong> Tiada rekod pembayaran yang telah disahkan.</div>
@else
  <table class="items-table" style="margin-bottom:4px;">
    <thead>
      <tr>
        <th style="width:5%;">#</th>
        <th style="width:49%;">Perihal</th>
        <th style="width:8%;">Ktt</th>
        <th style="width:19%;">Harga (RM)</th>
        <th style="width:19%; text-align:right;">Amaun (RM)</th>
      </tr>
    </thead>
    <tbody>
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
        <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
          <td>{{ $i + 1 }}</td>
          <td>
            <div class="item-name">{{ $typeLabel }}</div>
            <div class="item-desc">Liputan tahun: {!! $coverage !!}@if($p->yuran?->jenis_yuran) &nbsp;&middot;&nbsp;{{ $p->yuran->jenis_yuran }}@endif</div>
          </td>
          <td>1</td>
          <td>{{ number_format($lineTotal,2) }}</td>
          <td style="text-align:right; font-weight:600; color:#1a3c5e;">{{ number_format($lineTotal,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Totals --}}
  <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
    <tr>
      <td width="58%">&nbsp;</td>
      <td width="42%">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-top:2px solid #1a3c5e; margin-top:2px;">
          <tr>
            <td style="padding:8px 10px 4px 10px; font-size:13px; font-weight:800; color:#1a3c5e;">JUMLAH DIBAYAR</td>
            <td style="padding:8px 10px 4px 0; font-size:13px; font-weight:800; color:#2e7dd1; text-align:right;">RM {{ number_format($totalPaid,2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  {{-- Payment Method --}}
  @if($isSinglePayment)
    <div class="section-label" style="margin-bottom:6px;">Kaedah Pembayaran</div>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
      <tr>
        <td width="25%" style="padding-right:5px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
            <tr><td style="padding:8px 10px;"><div class="plbl">Kaedah</div><div class="pval">Pembayaran melalui sistem BAKIS</div></td></tr>
          </table>
        </td>
        <td width="25%" style="padding-left:5px; padding-right:5px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
            <tr><td style="padding:8px 10px;"><div class="plbl">Rujukan / No. Resit</div><div class="pval">{{ $payment->no_resit_sistem ?? '&mdash;' }}</div></td></tr>
          </table>
        </td>
        <td width="25%" style="padding-left:5px; padding-right:5px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; border:1px solid #e4eaf2; border-radius:6px;">
            <tr><td style="padding:8px 10px;"><div class="plbl">Tarikh Kelulusan</div><div class="pval">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '&mdash;' }}</div></td></tr>
          </table>
        </td>
        <td width="25%" style="padding-left:5px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#e8f5e9; border:1px solid #a5d6a7; border-radius:6px;">
            <tr><td style="padding:8px 10px;"><div class="plbl">Status</div><div class="pval-paid">&#10004; DIBAYAR</div></td></tr>
          </table>
        </td>
      </tr>
    </table>
  @endif
@endif

{{-- ═══════════════════════════════════
     NOTES
═══════════════════════════════════ --}}
<div class="notes-box" style="margin-bottom:12px;">
  <strong style="display:block; margin-bottom:4px; font-size:10px;">&#128204; Nota penting:</strong>
  1. Resit ini dijana komputer dan sah tanpa tandatangan fizikal.<br/>
  2. Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.<br/>
  3. Sila simpan resit ini untuk rujukan dan rekod anda.<br/>
  4. Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.
</div>

{{-- ═══════════════════════════════════
     FOOTER
═══════════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:1.5px dashed #c5d3e0; padding-top:8px; margin-top:4px;">
  <tr>
    <td width="55%" class="footer-lft">
      <strong style="color:#1a3c5e;">BAKIS</strong> &mdash; Sistem Pengurusan Keahlian<br/>
      Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone','UTC') }})
    </td>
    <td width="45%" class="footer-rgt">
      Disahkan oleh:
      <table cellpadding="0" cellspacing="0" style="margin-left:auto;">
        <tr>
          <td style="border-top:1px solid #c5d3e0; margin-top:18px; padding-top:4px; width:130px; text-align:center; font-size:9px; color:#8a9ab0;">
            <br/><br/>Pentadbir Keahlian
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
