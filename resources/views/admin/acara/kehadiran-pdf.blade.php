<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Senarai Kehadiran</title>
<style>
@page { size:A4 portrait; margin:12mm 14mm 14mm 14mm; }
body { font-family:'DejaVu Sans',sans-serif; font-size:10.5px; color:#1e293b; margin:0; padding:0; line-height:1.5; }
table { border-collapse:collapse; }
.logo-box { background-color:#1a3c5e; color:#fff; font-size:20px; font-weight:700; width:42px; height:42px; text-align:center; vertical-align:middle; border-radius:7px; }
.brand-name { font-size:19px; font-weight:700; color:#1a3c5e; padding-left:9px; vertical-align:middle; }
.doc-title { font-size:22px; font-weight:800; color:#1a3c5e; letter-spacing:1px; text-align:right; }
.doc-sub { font-size:10px; color:#64748b; text-align:right; padding-top:3px; }
.meta { background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:10px 14px; }
.meta-label { font-size:8.5px; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:1px; }
.meta-value { font-size:12px; font-weight:700; color:#1a3c5e; padding-bottom:6px; }
.itable { width:100%; }
.itable thead td { background-color:#1a3c5e; color:#fff; padding:8px 10px; font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
.itable tbody td { padding:8px 10px; border-bottom:1px solid #eef2f7; color:#334155; }
.itable .row-alt td { background-color:#f8fafc; }
.summary { background-color:#1a3c5e; color:#fff; font-size:13px; font-weight:700; padding:10px 14px; border-radius:6px; }
.empty { text-align:center; color:#94a3b8; padding:24px; font-size:11px; }
.foot { font-size:8.5px; color:#94a3b8; padding-top:14px; }
</style>
</head>
<body>
{{-- ACCENT STRIP --}}
<table width="100%" cellpadding="0" cellspacing="0">
  <tr><td height="5" style="background-color:#1a3c5e; font-size:1px; line-height:1px;">&nbsp;</td></tr>
</table>

{{-- HEADER --}}
<table width="100%" cellpadding="0" cellspacing="0" style="padding-top:10px; padding-bottom:9px; border-bottom:2px solid #1a3c5e; margin-bottom:12px;">
  <tr>
    <td width="55%" style="vertical-align:top;">
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td class="logo-box" width="42" height="42" align="center" valign="middle">B</td>
          <td class="brand-name">BAKIS</td>
        </tr>
        <tr><td colspan="2" style="font-size:9px; color:#7a8fa6; padding-top:3px; padding-left:51px;">Sistem Pengurusan Keahlian</td></tr>
      </table>
    </td>
    <td width="45%" style="vertical-align:top;">
      <div class="doc-title">SENARAI KEHADIRAN</div>
      <div class="doc-sub">Dijana: {{ now()->translatedFormat('d M Y, g:i A') }}</div>
    </td>
  </tr>
</table>

{{-- EVENT META --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
  <tr><td class="meta">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td width="60%">
          <div class="meta-label">Acara</div>
          <div class="meta-value">{{ $acara->nama_acara }}</div>
          <div class="meta-label">Lokasi</div>
          <div class="meta-value">{{ $acara->lokasi }}</div>
        </td>
        <td width="40%">
          <div class="meta-label">Waktu</div>
          <div class="meta-value">{{ $acara->waktu }}</div>
          <div class="meta-label">Kod Pautan</div>
          <div class="meta-value">{{ $acara->code }}</div>
        </td>
      </tr>
    </table>
  </td></tr>
</table>

{{-- ATTENDEES --}}
<table class="itable" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <td width="8%" align="center">No.</td>
      <td width="42%">Nama</td>
      <td width="25%">No. KP</td>
      <td width="25%">Waktu Hadir</td>
    </tr>
  </thead>
  <tbody>
    @forelse($kehadirans as $i => $hadir)
      <tr class="{{ $i % 2 === 1 ? 'row-alt' : '' }}">
        <td align="center">{{ $i + 1 }}</td>
        <td style="font-weight:700; color:#1a3c5e;">{{ $hadir->nama }}</td>
        <td>{{ $hadir->no_kp }}</td>
        <td>{{ $hadir->attended_at->translatedFormat('d M Y, g:i A') }}</td>
      </tr>
    @empty
      <tr><td colspan="4" class="empty">Tiada rekod kehadiran setakat ini.</td></tr>
    @endforelse
  </tbody>
</table>

{{-- SUMMARY --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px;">
  <tr><td align="right">
    <table cellpadding="0" cellspacing="0"><tr><td class="summary">Jumlah Kehadiran: {{ $kehadirans->count() }}</td></tr></table>
  </td></tr>
</table>

<div class="foot">Dokumen ini dijana secara elektronik oleh Sistem BAKIS dan sah tanpa tandatangan.</div>
</body>
</html>
