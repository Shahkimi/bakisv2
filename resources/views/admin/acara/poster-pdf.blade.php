<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Poster Acara</title>
<style>
@page { size:A4 portrait; margin:0; }
body { font-family:'DejaVu Sans',sans-serif; margin:0; padding:0; background:#ffffff; }
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════
     MASTER WRAPPER
═══════════════════════════════════════════════════════ --}}
<table width="595" cellpadding="0" cellspacing="0" style="width:595px; margin:0 auto; background:#ffffff;">

  {{-- ── TOP DECORATIVE STRIPE ── --}}
  <tr>
    <td style="background-color:#1e40af; height:6px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="background-color:#3b82f6; height:3px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>

  {{-- ── HEADER ── --}}
  <tr>
    <td style="background-color:#0f172a; padding:22px 36px;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          {{-- Logo badge --}}
          <td width="50" valign="middle">
            <table cellpadding="0" cellspacing="0" width="46" height="46"
                   style="background-color:#3b82f6; border-radius:10px;">
              <tr>
                <td align="center" valign="middle"
                    style="font-size:22px; font-weight:800; color:#ffffff; line-height:46px;">B</td>
              </tr>
            </table>
          </td>
          {{-- Brand --}}
          <td valign="middle" style="padding-left:12px;">
            <div style="font-size:19px; font-weight:800; color:#ffffff; letter-spacing:2px;">BAKIS</div>
            <div style="font-size:9px; color:#94a3b8; letter-spacing:1px; margin-top:2px;">SISTEM PENGURUSAN KEAHLIAN</div>
          </td>
          {{-- Right label --}}
          <td align="right" valign="middle">
            <div style="font-size:8.5px; font-weight:700; color:#3b82f6; letter-spacing:2.5px; text-transform:uppercase;">POSTER ACARA</div>
            <div style="font-size:8px; color:#475569; margin-top:4px;">{{ now()->format('d M Y') }}</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── HERO TITLE AREA ── --}}
  <tr>
    <td style="background-color:#f8fafc; padding:36px 36px 10px; text-align:center;">
      {{-- Kicker badge --}}
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" style="padding-bottom:14px;">
            <table cellpadding="0" cellspacing="0" style="border:1.5px solid #93c5fd; border-radius:999px; background-color:#eff6ff;">
              <tr>
                <td style="padding:5px 22px; font-size:9.5px; font-weight:700; color:#1d4ed8; letter-spacing:3px; text-transform:uppercase;">
                  &#9733;&nbsp; JEMPUTAN KEHADIRAN &nbsp;&#9733;
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      {{-- Event title --}}
      <div style="font-size:32px; font-weight:800; color:#0f172a; line-height:1.2; padding:0 20px 18px;">{{ $acara->nama_acara }}</div>
      {{-- Blue rule --}}
      <table cellpadding="0" cellspacing="0" style="margin:0 auto; margin-bottom:10px;">
        <tr>
          <td style="width:28px; height:4px; background-color:#1e40af; border-radius:2px; font-size:1px; line-height:1px;">&nbsp;</td>
          <td style="width:8px;">&nbsp;</td>
          <td style="width:60px; height:4px; background-color:#3b82f6; border-radius:2px; font-size:1px; line-height:1px;">&nbsp;</td>
          <td style="width:8px;">&nbsp;</td>
          <td style="width:28px; height:4px; background-color:#1e40af; border-radius:2px; font-size:1px; line-height:1px;">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── SEPARATOR ── --}}
  <tr>
    <td style="background-color:#e2e8f0; height:1px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>

  {{-- ── INFO CARDS ROW ── --}}
  <tr>
    <td style="background-color:#f8fafc; padding:22px 36px 20px;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>

          {{-- LOKASI card --}}
          <td width="49%" valign="top"
              style="background-color:#ffffff; border:1px solid #dbeafe; border-left:4px solid #3b82f6;
                     border-radius:10px; padding:14px 16px;">
            <div style="font-size:8.5px; font-weight:700; color:#3b82f6; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">
              &#128205;&nbsp; LOKASI
            </div>
            <div style="font-size:14px; font-weight:700; color:#0f172a; line-height:1.35;">{{ $acara->lokasi }}</div>
          </td>

          <td width="2%">&nbsp;</td>

          {{-- WAKTU card --}}
          <td width="49%" valign="top"
              style="background-color:#ffffff; border:1px solid #ede9fe; border-left:4px solid #7c3aed;
                     border-radius:10px; padding:14px 16px;">
            <div style="font-size:8.5px; font-weight:700; color:#7c3aed; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">
              &#128336;&nbsp; WAKTU
            </div>
            <div style="font-size:14px; font-weight:700; color:#0f172a; line-height:1.35;">{{ $acara->waktu }}</div>
          </td>

        </tr>
      </table>
    </td>
  </tr>

  {{-- ── QR INSTRUCTION BAND ── --}}
  <tr>
    <td style="background-color:#1e40af; padding:11px 36px; text-align:center;">
      <div style="font-size:11px; font-weight:700; color:#ffffff; letter-spacing:1.5px; text-transform:uppercase;">
        &#128247;&nbsp; IMBAS KOD QR DI BAWAH UNTUK MEREKOD KEHADIRAN
      </div>
    </td>
  </tr>

  {{-- ── QR CODE ── --}}
  <tr>
    <td style="background-color:#f8fafc; padding:28px 36px 20px; text-align:center;">
      {{-- Outer decorative frame --}}
      <table cellpadding="0" cellspacing="0" style="margin:0 auto; border:2px solid #bfdbfe; border-radius:16px; background-color:#ffffff; padding:20px;">
        <tr>
          <td>
            {{-- Corner top --}}
            <table cellpadding="0" cellspacing="0" style="margin-bottom:4px;">
              <tr>
                <td style="width:20px; height:20px; border-top:3px solid #3b82f6; border-left:3px solid #3b82f6; border-radius:4px 0 0 0; font-size:1px;">&nbsp;</td>
                <td style="width:220px;">&nbsp;</td>
                <td style="width:20px; height:20px; border-top:3px solid #3b82f6; border-right:3px solid #3b82f6; border-radius:0 4px 0 0; font-size:1px;">&nbsp;</td>
              </tr>
            </table>
            {{-- QR code as PNG image --}}
            <div style="width:260px; height:260px; margin:0 auto;">
              <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" width="260" height="260" style="display:block;" />
            </div>
            {{-- Corner bottom --}}
            <table cellpadding="0" cellspacing="0" style="margin-top:4px;">
              <tr>
                <td style="width:20px; height:20px; border-bottom:3px solid #3b82f6; border-left:3px solid #3b82f6; border-radius:0 0 0 4px; font-size:1px;">&nbsp;</td>
                <td style="width:220px;">&nbsp;</td>
                <td style="width:20px; height:20px; border-bottom:3px solid #3b82f6; border-right:3px solid #3b82f6; border-radius:0 0 4px 0; font-size:1px;">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      {{-- URL + hint --}}
      <div style="font-size:13px; font-weight:700; color:#1d4ed8; margin-top:14px; letter-spacing:0.3px;">{{ $acara->publicUrl() }}</div>
      <div style="font-size:10px; color:#64748b; margin-top:4px;">Masukkan No. Kad Pengenalan selepas mengimbas</div>
    </td>
  </tr>

  {{-- ── BOTTOM SEPARATOR ── --}}
  <tr>
    <td style="background-color:#e2e8f0; height:1px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>

  {{-- ── FOOTER ── --}}
  <tr>
    <td style="background-color:#0f172a; padding:12px 36px;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left">
            <span style="font-size:8.5px; color:#64748b;">Poster dijana secara automatik oleh </span>
            <span style="font-size:8.5px; font-weight:700; color:#3b82f6;">Sistem BAKIS</span>
          </td>
          <td align="right">
            <span style="font-size:8.5px; color:#475569;">{{ now()->format('d M Y, H:i') }}</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── BOTTOM STRIPE ── --}}
  <tr>
    <td style="background-color:#3b82f6; height:3px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="background-color:#1e40af; height:6px; font-size:1px; line-height:1px;">&nbsp;</td>
  </tr>

</table>

</body>
</html>
