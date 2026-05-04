<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Resit Keahlian</title>
<style>
@page { size:A4 portrait; margin:12mm 14mm 10mm 14mm; }
body { font-family:'DejaVu Sans',sans-serif; font-size:10.5px; color:#1e293b; background:#fff; margin:0; padding:0; line-height:1.5; }
table { border-collapse:collapse; }

/* Header */
.logo-box  { background-color:#1a3c5e; color:#fff; font-size:20px; font-weight:700; width:42px; height:42px; text-align:center; vertical-align:middle; border-radius:7px; }
.brand-name{ font-size:19px; font-weight:700; color:#1a3c5e; padding-left:9px; vertical-align:middle; }
.rtitle    { font-size:24px; font-weight:800; color:#1a3c5e; letter-spacing:1px; text-align:right; }
.rno       { font-size:11px; color:#2563eb; font-weight:700; text-align:right; padding-top:3px; }
.rdate     { font-size:9px; color:#94a3b8; text-align:right; padding-top:2px; }

/* Banner */
.banner-ok  { font-size:11px; font-weight:700; color:#15803d; }
.banner-sub { font-size:9.5px; color:#166534; padding-top:2px; }

/* Section label */
.slabel { font-size:8.5px; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:1px; }

/* Items table */
.itable thead td { background-color:#1a3c5e; color:#fff; padding:7px 9px; font-size:9.5px; font-weight:700; }
.itable tbody td { padding:8px 9px; border-bottom:1px solid #f1f5f9; color:#334155; }
.itable .row-alt td { background-color:#f8fafc; }
.iname { font-weight:700; color:#1a3c5e; }
.idesc { font-size:8.5px; color:#94a3b8; padding-top:2px; }

/* Notes */
.notes { background-color:#fffbeb; border:1px solid #fde68a; border-radius:5px; padding:8px 12px; font-size:9.5px; color:#92400e; line-height:1.65; }

/* Footer */
.foot-l { font-size:8.5px; color:#94a3b8; line-height:1.7; vertical-align:bottom; }
.foot-r { font-size:8.5px; color:#94a3b8; text-align:right; vertical-align:bottom; }
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
    $isActive        = str_contains(strtolower($statusName), 'aktif');
@endphp

{{-- ▌TOP ACCENT --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0;">
  <tr><td height="5" style="background-color:#1a3c5e; font-size:1px; line-height:1px;">&nbsp;</td></tr>
</table>

{{-- ▌HEADER --}}
<table width="100%" cellpadding="0" cellspacing="0" style="padding-top:10px; padding-bottom:9px; border-bottom:2px solid #1a3c5e; margin-bottom:10px;">
  <tr>
    <td width="62%" style="vertical-align:top;">
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td class="logo-box" width="42" height="42" align="center" valign="middle">B</td>
          <td class="brand-name">BAKIS</td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:9px; color:#7a8fa6; padding-top:3px; padding-left:51px;">Sistem Pengurusan Keahlian</td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:8.5px; color:#94a3b8; padding-left:51px;">Dokumen resit dijana secara elektronik.</td>
        </tr>
      </table>
    </td>
    <td width="38%" style="vertical-align:top;">
      <div class="rtitle">RESIT</div>
      <div class="rno"># {{ $receiptNo }}</div>
      <div class="rdate">Dikeluarkan: {{ $issuedAt->format('d M Y') }}</div>
    </td>
  </tr>
</table>

{{-- ▌STATUS BANNER --}}
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#dcfce7; border-left:4px solid #16a34a; border-radius:5px; margin-bottom:12px;">
  <tr>
    <td style="padding:9px 14px;">
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td width="16" style="vertical-align:middle;">
            <table cellpadding="0" cellspacing="0"><tr><td width="10" height="10" style="background-color:#16a34a; border-radius:5px; font-size:1px;">&nbsp;</td></tr></table>
          </td>
          <td style="padding-left:8px; vertical-align:middle;">
            @if($isSinglePayment)
              <div class="banner-ok">&#10004; Bayaran diterima &amp; disahkan</div>
              <div class="banner-sub">Resit rasmi bagi pembayaran yuran keahlian &mdash; {{ match($payment->jenis ?? '') { 'pendaftaran_baru'=>'Pendaftaran Baru','pembaharuan'=>'Pembaharuan',default=>'Keahlian' } }}</div>
            @else
              <div class="banner-ok">&#10004; Ringkasan pembayaran disahkan</div>
              <div class="banner-sub">Status: {{ $statusName }} &mdash; {{ $approvedPayments->count() }} rekod disahkan</div>
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{{-- ▌▌▌ MAKLUMAT AHLI — REDESIGNED SECTION ▌▌▌ --}}
{{-- Outer card with dark header bar --}}
<table width="100%" cellpadding="0" cellspacing="0" style="border:1.5px solid #e2e8f0; border-radius:8px; margin-bottom:12px;">

  {{-- Card header bar --}}
  <tr>
    <td colspan="3" style="background-color:#1a3c5e; border-radius:6px 6px 0 0; padding:9px 14px;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="vertical-align:middle;">
            <span style="font-size:11px; font-weight:700; color:#ffffff; letter-spacing:0.5px;">&#128100;&nbsp; MAKLUMAT AHLI &amp; PENGEBILAN</span>
          </td>
          <td style="text-align:right; vertical-align:middle;">
            {{-- Active badge --}}
            <span style="background-color:{{ $isActive ? '#16a34a' : '#dc2626' }}; color:#ffffff; font-size:8.5px; font-weight:700; padding:2px 9px; border-radius:20px;">{{ strtoupper($statusName) }}</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Row 1: Nama Ahli (full width with accent left border) --}}
  <tr>
    <td colspan="3" style="padding:0; border-bottom:1px solid #f1f5f9;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="4" style="background-color:#2563eb; font-size:1px;">&nbsp;</td>
          <td style="padding:9px 14px;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">Nama Penuh Ahli</div>
            <div style="font-size:13px; color:#1a3c5e; font-weight:700; padding-top:2px;">{{ $member->nama }}</div>
          </td>
          <td width="50%" style="padding:9px 14px; border-left:1px solid #f1f5f9;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">No. Ahli</div>
            <div style="font-size:13px; color:#2563eb; font-weight:700; padding-top:2px; letter-spacing:0.5px;">{{ $member->no_ahli ?? '&mdash;' }}</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Row 2: E-mel | No. Telefon --}}
  <tr>
    <td colspan="3" style="padding:0; border-bottom:1px solid #f1f5f9;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="4" style="background-color:#e2e8f0; font-size:1px;">&nbsp;</td>
          <td style="padding:8px 14px;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">E-mel</div>
            <div style="font-size:11px; color:#334155; font-weight:600; padding-top:2px;">{{ $member->email ?? '&mdash;' }}</div>
          </td>
          <td width="50%" style="padding:8px 14px; border-left:1px solid #f1f5f9;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">No. Telefon / HP</div>
            <div style="font-size:11px; color:#334155; font-weight:600; padding-top:2px;">{{ $member->no_hp ?? $member->no_tel ?? '&mdash;' }}</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Row 3: No. KP | Member Since --}}
  <tr>
    <td colspan="3" style="padding:0;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="4" style="background-color:#e2e8f0; font-size:1px; border-radius:0 0 0 6px;">&nbsp;</td>
          <td style="padding:8px 14px;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">No. Kad Pengenalan</div>
            <div style="font-size:11px; color:#334155; font-weight:600; padding-top:2px;">{{ $member->no_kp ?? '&mdash;' }}</div>
          </td>
          <td width="50%" style="padding:8px 14px; border-left:1px solid #f1f5f9;">
            <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.7px;">Tarikh Daftar / ID Dalaman</div>
            <div style="font-size:11px; color:#334155; font-weight:600; padding-top:2px;">{{ $member->created_at ? $member->created_at->format('d M Y') : '&mdash;' }}</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>
{{-- ▌▌▌ END MAKLUMAT AHLI ▌▌▌ --}}


{{-- ▌BUTIRAN PEMBAYARAN --}}
<div class="slabel" style="margin-bottom:6px;">Butiran Pembayaran</div>

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
  <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
    <tr>
      <td width="56%">&nbsp;</td>
      <td width="44%">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-top:2px solid #1a3c5e;">
          <tr>
            <td style="padding:7px 9px 4px 9px; font-size:12px; font-weight:800; color:#1a3c5e;">JUMLAH DIBAYAR</td>
            <td style="padding:7px 0 4px 0; font-size:14px; font-weight:800; color:#2563eb; text-align:right;">RM&nbsp;{{ number_format($totalPaid,2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  {{-- PAYMENT METHOD --}}
  @if($isSinglePayment)
    <div class="slabel" style="margin-bottom:6px;">Kaedah Pembayaran</div>
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1.5px solid #e2e8f0; border-radius:6px; margin-bottom:12px;">
      <tr>
        <td width="25%" style="padding:9px 12px; border-right:1px solid #f1f5f9;">
          <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Kaedah</div>
          <div style="font-size:10px; color:#1a3c5e; font-weight:600; padding-top:3px;">Sistem BAKIS</div>
        </td>
        <td width="25%" style="padding:9px 12px; border-right:1px solid #f1f5f9;">
          <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Rujukan / No. Resit</div>
          <div style="font-size:10px; color:#1a3c5e; font-weight:600; padding-top:3px;">{{ $payment->no_resit_sistem ?? '&mdash;' }}</div>
        </td>
        <td width="25%" style="padding:9px 12px; border-right:1px solid #f1f5f9;">
          <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Tarikh Kelulusan</div>
          <div style="font-size:10px; color:#1a3c5e; font-weight:600; padding-top:3px;">{{ ($payment->approved_at ?? $payment->updated_at)?->format('d M Y, H:i') ?? '&mdash;' }}</div>
        </td>
        <td width="25%" style="padding:9px 12px; background-color:#dcfce7; border-radius:0 5px 5px 0;">
          <div style="font-size:8px; color:#166534; text-transform:uppercase; letter-spacing:0.5px;">Status</div>
          <div style="font-size:10px; color:#15803d; font-weight:700; padding-top:3px;">&#10004; DIBAYAR</div>
        </td>
      </tr>
    </table>
  @endif
@endif

{{-- ▌NOTES --}}
<div class="notes" style="margin-bottom:10px;">
  <strong style="display:block; margin-bottom:3px; font-size:10px;">&#128204; Nota Penting:</strong>
  1. Resit ini dijana komputer dan sah tanpa tandatangan fizikal.<br/>
  2. Faedah keahlian berkuat kuasa mengikut tarikh kelulusan pembayaran.<br/>
  3. Sila simpan resit ini untuk rujukan dan rekod anda.<br/>
  4. Untuk pertanyaan, hubungi pentadbir melalui saluran rasmi organisasi.
</div>

{{-- ▌FOOTER --}}
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:1.5px dashed #cbd5e1; padding-top:7px; margin-top:4px;">
  <tr>
    <td class="foot-l">
      <strong style="color:#1a3c5e;">BAKIS</strong> &mdash; Sistem Pengurusan Keahlian<br/>
      Dijana: {{ now()->format('d M Y, H:i') }} ({{ config('app.timezone','UTC') }})
    </td>
    <td class="foot-r">
      Disahkan oleh:
      <table cellpadding="0" cellspacing="0" style="margin-left:auto; margin-top:14px;">
        <tr><td style="border-top:1px solid #cbd5e1; padding-top:4px; width:120px; text-align:center; font-size:8.5px; color:#94a3b8;">Pentadbir Keahlian</td></tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
