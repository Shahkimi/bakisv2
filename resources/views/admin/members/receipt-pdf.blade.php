<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Resit Keahlian</title>
<style>
@page { size:A4 portrait; margin:12mm 14mm 10mm 14mm; }
body { font-family:'DejaVu Sans',sans-serif; font-size:10.5px; color:#1a1a2e; background:#fff; margin:0; padding:0; line-height:1.5; }
table { border-collapse:collapse; }

/* ── Accent strip ── */
.top-strip { background-color:#1a3c5e; height:5px; width:100%; font-size:1px; line-height:1px; }

/* ── Header ── */
.header-wrap { padding:10px 0 8px 0; border-bottom:2px solid #1a3c5e; margin-bottom:10px; }
.logo-box { background-color:#1a3c5e; color:#fff; font-size:20px; font-weight:700;
            width:42px; height:42px; text-align:center; vertical-align:middle; border-radius:7px; }
.brand-name { font-size:19px; font-weight:700; color:#1a3c5e; padding-left:9px; vertical-align:middle; }
.brand-sub { font-size:9px; color:#7a8fa6; padding-top:3px; }
.rtitle { font-size:24px; font-weight:800; color:#1a3c5e; letter-spacing:1px; text-align:right; }
.rno    { font-size:11px; color:#2563eb; font-weight:700; text-align:right; padding-top:3px; }
.rdate  { font-size:9px; color:#94a3b8; text-align:right; padding-top:2px; }

/* ── Status banner ── */
.banner { background-color:#dcfce7; border-left:4px solid #16a34a; border-radius:5px;
          padding:8px 12px; margin-bottom:10px; }
.banner-ok  { font-size:11px; font-weight:700; color:#15803d; }
.banner-sub { font-size:9.5px; color:#166534; padding-top:2px; }

/* ── Section label ── */
.slabel { font-size:8.5px; font-weight:700; color:#2563eb; text-transform:uppercase;
          letter-spacing:1px; padding-bottom:5px; padding-top:10px; }

/* ── Info cards ── */
.card { background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:5px;
        padding:7px 10px; width:100%; }
.clabel { font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.6px; }
.cval   { font-size:11.5px; color:#1a3c5e; font-weight:600; padding-top:1px; }
.cval-b { font-size:11.5px; color:#2563eb; font-weight:700; padding-top:1px; }
.cval-g { font-size:11.5px; color:#16a34a; font-weight:700; padding-top:1px; }

/* ── Items ── */
.itable { width:100%; font-size:10.5px; }
.itable thead td { background-color:#1a3c5e; color:#fff; padding:7px 9px;
                   font-size:9.5px; font-weight:700; }
.itable tbody td { padding:8px 9px; border-bottom:1px solid #f1f5f9; color:#334155; }
.itable .row-alt td { background-color:#f8fafc; }
.iname { font-weight:700; color:#1a3c5e; }
.idesc { font-size:8.5px; color:#94a3b8; padding-top:2px; }

/* ── Totals ── */
.tot-label  { font-size:12px; font-weight:800; color:#1a3c5e; padding:7px 9px 0 9px; }
.tot-amount { font-size:14px; font-weight:800; color:#2563eb; text-align:right; padding:7px 0 0 9px; }
.tot-border { border-top:2px solid #1a3c5e; }

/* ── Pay boxes ── */
.pcard { background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:5px; padding:7px 9px; width:100%; }
.pcard-ok { background-color:#dcfce7; border:1px solid #86efac; border-radius:5px; padding:7px 9px; width:100%; }
.plabel { font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; }
.pval   { font-size:10px; color:#1a3c5e; font-weight:600; padding-top:2px; word-wrap:break-word; }
.pval-g { font-size:10px; color:#16a34a; font-weight:700; padding-top:2px; }

/* ── Notes ── */
.notes { background-color:#fffbeb; border:1px solid #fde68a; border-radius:5px;
         padding:8px 12px; font-size:9.5px; color:#92400e; line-height:1.65; margin-bottom:10px; }
.notes-title { font-weight:700; display:block; margin-bottom:3px; }

/* ── Footer ── */
.footer-line { border-top:1.5px dashed #cbd5e1; padding-top:7px; margin-top:8px; }
.foot-l { font-size:8.5px; color:#94a3b8; line-height:1.7; vertical-align:bottom; }
.foot-r { font-size:8.5px; color:#94a3b8; text-align:right; vertical-align:bottom; }
.sig-box { border-top:1px solid #cbd5e1; padding-top:4px; text-align:center;
           font-size:8.5px; color:#94a3b8; width:120px; margin-top:16px; }
</style>
</head>
<body>
@php
    $isSinglePayment = isset($payment);
    if (!$member->relationLoaded('payments')) {
        $member->load(['payments.yuran']);
    }
    $approvedPayments = $member->payments->where('status', \App\Models\Payment::STATUS_APPROVED)->values();
    if ($isSinglePayment) $approvedPayments = collect([$payment]);
    $totalPaid       = $approvedPayments->sum(fn($p) => (float)($p->jumlah ?? 0));
    $receiptYear     = $isSinglePayment ? (int)$payment->tahun_bayar : (int)now()->year;
    $receiptNoSuffix = str_pad((string)($isSinglePayment ? $payment->id : $member->id), 6, '0', STR_PAD_LEFT);
    $receiptNo       = 'RCP-'.$receiptYear.'-'.$receiptNoSuffix;
    $issuedAt        = ($isSinglePayment && $payment->approved_at) ? $payment->approved_at : now();
    $statusName      = $member->memberStatus?->name ?? 'Tidak Aktif';
@endphp

{{-- TOP ACCENT STRIP --}}
<div class="top-strip">&nbsp;</div>

{{-- ═══════════════════════════════ HEADER ═══════════════════════════════ --}}
<div class="header-wrap">
<table width="100%">
<tr>
  <td width="62%" style="vertical-align:top;">
    <table><tr>
      <td class="logo-box" width="42" height="42">B</td>
      <td class="brand-name">BAKIS</td>
    </tr></table>
    <div class="brand-sub" style="margin-left:51px;">Sistem Pengurusan Keahlian</div>
    <div style="font-size:8.5px; color:#94a3b8; margin-left:51px;">Dokumen resit dijana secara elektronik.</div>
  </td>
  <td width="38%" style="vertical-align:top;">
    <div class="rtitle">RESIT</div>
    <div class="rno"># {{ $receiptNo }}</div>
    <div class="rdate">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</div>
  </td>
</tr>
</table>
</div>

{{-- ═══════════════════════════════ STATUS BANNER ═══════════════════════════════ --}}
<div class="banner">
  @if($isSinglePayment)
    <div class="banner-ok">&#10004;&nbsp; Bayaran diterima &amp; disahkan</div>
    <div class="banner-sub">Resit rasmi bagi pembayaran yuran keahlian &mdash; {{ match($payment->jenis ?? '') {
      'pendaftaran_baru' => 'Pendaftaran Baru',
      'pembaharuan'      => 'Pembaharuan',
      default            => 'Keahlian',
    } }}</div>
  @else
    <div class="banner-ok">&#10004;&nbsp; Ringkasan pembayaran disahkan</div>
    <div class="banner-sub">Status: {{ $statusName }} &mdash; {{ $approvedPayments->count() }} rekod disahkan</div>
  @endif
</div>

{{-- ═══════════════════════════════ MEMBER INFO ═══════════════════════════════ --}}
<div class="slabel">Maklumat Ahli &amp; Pengebilan</div>

{{-- Row 1 --}}
<table width="100%" style="margin-bottom:4px;">
<tr>
  <td width="49.5%">
    <div class="card"><div class="clabel">Nama Ahli</div><div class="cval">{{ $member->nama }}</div></div>
  </td>
  <td width="1%">&nbsp;</td>
  <td width="49.5%">
    <div class="card"><div class="clabel">No. Ahli</div><div class="cval-b">{{ $member->no_ahli ?? '&mdash;' }}</div></div>
  </td>
</tr>
</table>

{{-- Row 2 --}}
<table width="100%" style="margin-bottom:4px;">
<tr>
  <td width="49.5%">
    <div class="card"><div class="clabel">E-mel</div><div class="cval">{{ $member->email ?? '&mdash;' }}</div></div>
  </td>
  <td width="1%">&nbsp;</td>
  <td width="49.5%">
    <div class="card"><div class="clabel">No. Telefon / HP</div><div class="cval">{{ $member->no_hp ?? $member->no_tel ?? '&mdash;' }}</div></div>
  </td>
</tr>
</table>

{{-- Row 3 --}}
<table width="100%" style="margin-bottom:10px;">
<tr>
  <td width="49.5%">
    <div class="card"><div class="clabel">No. KP</div><div class="cval">{{ $member->no_kp ?? '&mdash;' }}</div></div>
  </td>
  <td width="1%">&nbsp;</td>
  <td width="49.5%">
    <div class="card"><div class="clabel">Status Keahlian</div><div class="cval-g">&#9679;&nbsp;{{ $statusName }}</div></div>
  </td>
</tr>
</table>

{{-- ═══════════════════════════════ PAYMENT ITEMS ═══════════════════════════════ --}}
<div class="slabel">Butiran Pembayaran</div>

@if($approvedPayments->isEmpty())
  <div class="notes" style="margin-bottom:10px;">Tiada rekod pembayaran yang telah disahkan.</div>
@else
  <table class="itable" width="100%" style="margin-bottom:4px;">
    <thead>
      <tr>
        <td width="5%">#</td>
        <td width="49%">Perihal</td>
        <td width="8%">Ktt</td>
        <td width="19%">Harga (RM)</td>
        <td width="19%" style="text-align:right;">Amaun (RM)</td>
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
        <tr class="{{ $i % 2 === 1 ? 'row-alt' : '' }}">
          <td>{{ $i+1 }}</td>
          <td>
            <div class="iname">{{ $typeLabel }}</div>
            <div class="idesc">Liputan tahun: {!! $coverage !!}@if($p->yuran?->jenis_yuran) &nbsp;&middot;&nbsp;{{ $p->yuran->jenis_yuran }}@endif</div>
          </td>
          <td>1</td>
          <td>{{ number_format($lineTotal,2) }}</td>
          <td style="text-align:right; font-weight:700; color:#1a3c5e;">{{ number_format($lineTotal,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- TOTALS --}}
  <table width="100%" style="margin-bottom:12px;">
  <tr>
    <td width="56%">&nbsp;</td>
    <td width="44%">
      <table width="100%">
        <tr class="tot-border">
          <td class="tot-label">JUMLAH DIBAYAR</td>
          <td class="tot-amount">RM&nbsp;{{ number_format($totalPaid,2) }}</td>
        </tr>
      </table>
    </td>
  </tr>
  </table>

  {{-- PAYMENT METHOD --}}
  @if($isSinglePayment)
    <div class="slabel">Kaedah Pembayaran</div>
    <table width="100%" style="margin-bottom:12px;">
    <tr>
      <td width="24.25%">
        <div class="pcard"><div class="plabel">Kaedah</div><div class="pval">Pembayaran melalui sistem BAKIS</div></div>
      </td>
      <td width="0.5%">&nbsp;</td>
      <td width="24.25%">
        <div class="pcard"><div class="plabel">Rujukan / No. Resit</div><div class="pval">{{ $payment->no_resit_sistem ?? '&mdash;' }}</div></div>
      </td>
      <td width="0.5%">&nbsp;</td>
      <td width="24.25%">
        <div class="pcard"><div class="plabel">Tarikh Kelulusan</div><div class="pval">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '&mdash;' }}</div></div>
      </td>
      <td width="0.5%">&nbsp;</td>
      <td width="24.25%">
        <div class="pcard-ok"><div class="plabel">Status</div><div class="pval-g">&#10004;&nbsp;DIBAYAR</div></div>
      </td>
    </tr>
    </table>
  @endif
@endif

{{-- ═══════════════════════════════ NOTES ═══════════════════════════════ --}}
<div class="notes">
  <span class="notes-title">&#128204;&nbsp;Nota Penting:</span>
  1. Resit ini dijana komputer dan sah tanpa tandatangan fizikal.<br/>
  2. Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.<br/>
  3. Sila simpan resit ini untuk rujukan dan rekod anda.<br/>
  4. Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.
</div>

{{-- ═══════════════════════════════ FOOTER ═══════════════════════════════ --}}
<table width="100%" class="footer-line">
<tr>
  <td class="foot-l">
    <strong style="color:#1a3c5e;">BAKIS</strong> &mdash; Sistem Pengurusan Keahlian<br/>
    Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone','UTC') }})
  </td>
  <td class="foot-r">
    <div>Disahkan oleh:</div>
    <div class="sig-box">Pentadbir Keahlian</div>
  </td>
</tr>
</table>

</body>
</html>
