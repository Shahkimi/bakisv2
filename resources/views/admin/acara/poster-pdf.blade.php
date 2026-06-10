<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster Acara BAKIS</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            background: #ffffff;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Full content-area height table so the poster is centered on the A4 page. */
        .page-center {
            width: 100%;
            height: 273mm;
            border-collapse: collapse;
        }

        .page-cell {
            height: 273mm;
            text-align: center;
            vertical-align: middle;
        }

        .poster {
            width: 186mm;
            margin: 0 auto;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #d9e2ec;
            border-radius: 18px;
        }

        .poster-content {
            padding: 22px;
            text-align: left;
        }

        .top-accent {
            height: 8px;
            background: #0f766e;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .header-table,
        .info-table,
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 70%;
            vertical-align: top;
            padding-right: 18px;
        }

        .header-right {
            width: 30%;
            vertical-align: top;
            text-align: right;
        }

        .brand-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #e6fffb;
            color: #115e59;
            border: 1px solid #99f6e4;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .system-title {
            margin: 0;
            font-size: 22px;
            line-height: 1.15;
            color: #0f172a;
        }

        .system-subtitle {
            margin: 6px 0 0;
            color: #475569;
            font-size: 13px;
        }

        .org-logo {
            display: block;
            width: 96px;
            height: 96px;
            margin-left: auto;
            object-fit: contain;
            border-radius: 16px;
        }

        .hero {
            margin-top: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px;
        }

        .hero-label {
            margin: 0 0 8px;
            color: #0f766e;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .hero-title {
            margin: 0;
            font-size: 28px;
            line-height: 1.12;
            color: #0f172a;
        }

        .hero-desc {
            margin: 8px 0 0;
            color: #475569;
            font-size: 13px;
        }

        .info-table {
            margin-top: 14px;
        }

        .info-card-wrap {
            width: 50%;
            padding-right: 10px;
            vertical-align: top;
        }

        .info-card-wrap.last {
            padding-right: 0;
            padding-left: 10px;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #dbe4ee;
            border-top: 3px solid #0f766e;
            border-radius: 14px;
            padding: 14px;
        }

        .info-label {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            font-weight: bold;
        }

        .info-value {
            margin: 0;
            color: #0f172a;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.35;
        }

        .info-note {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 11px;
        }

        .attendance-box {
            margin-top: 16px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 18px;
            padding: 18px;
        }

        .attendance-title {
            margin: 0 0 8px;
            font-size: 17px;
            line-height: 1.3;
        }

        .attendance-desc {
            margin: 0 0 14px;
            color: #cbd5e1;
            font-size: 12px;
        }

        .qr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-left {
            width: 150px;
            vertical-align: top;
        }

        .qr-right {
            vertical-align: top;
            padding-left: 18px;
        }

        .qr-frame {
            width: 144px;
            height: 144px;
            border: 3px solid #ffffff;
            background: #ffffff;
            border-radius: 14px;
            padding: 6px;
        }

        .qr-frame-inner {
            width: 100%;
            height: 132px;
            border-collapse: collapse;
        }

        .qr-frame-inner td {
            text-align: center;
            vertical-align: middle;
        }

        .qr-frame img {
            display: block;
            width: 132px;
            height: 132px;
            margin: 0 auto;
        }

        .scan-label {
            margin: 0 0 8px;
            color: #5eead4;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.9px;
            font-weight: bold;
        }

        .scan-url {
            margin: 0 0 10px;
            font-size: 16px;
            line-height: 1.45;
            font-weight: bold;
            word-break: break-word;
        }

        .scan-help,
        .scan-validity {
            margin: 0 0 6px;
            color: #cbd5e1;
            font-size: 12px;
            line-height: 1.5;
        }

        .footer-table {
            margin-top: 16px;
        }

        .footer-left,
        .footer-right {
            vertical-align: middle;
        }

        .footer-right {
            text-align: right;
        }

        .status-pill {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-pill.open {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #166534;
        }

        .status-pill.closed {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .generated-note {
            margin: 0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.5;
        }

        .muted-separator {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <table class="page-center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="page-cell" align="center" valign="middle" height="273mm">
                <table class="poster" width="186mm" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="poster-content">
                    <div class="top-accent"></div>

                    <table class="header-table">
                        <tr>
                            <td class="header-left">
                                <div class="brand-badge">BAKIS</div>
                                <h1 class="system-title">Sistem Pengurusan Keahlian<br>Poster Acara</h1>
                                <p class="system-subtitle">Reka bentuk poster acara yang lebih kemas, profesional dan sesuai untuk cetakan PDF.</p>
                            </td>
                            <td class="header-right">
                                @if ($logoDataUri)
                                    <img src="{{ $logoDataUri }}" alt="Logo Organisasi" class="org-logo" />
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="hero">
                        <p class="hero-label">Jemputan Kehadiran</p>
                        <h2 class="hero-title">{{ $acara->nama_acara }}</h2>
                        <p class="hero-desc">Sila hadir mengikut maklumat acara di bawah dan rekodkan kehadiran melalui kod QR yang disediakan.</p>

                        <table class="info-table">
                            <tr>
                                <td class="info-card-wrap">
                                    <div class="info-card">
                                        <p class="info-label">Lokasi</p>
                                        <p class="info-value">{{ $acara->lokasi }}</p>
                                        <p class="info-note">Sila hadir lebih awal untuk pendaftaran.</p>
                                    </div>
                                </td>
                                <td class="info-card-wrap last">
                                    <div class="info-card">
                                        <p class="info-label">Tarikh &amp; Waktu</p>
                                        <p class="info-value">{{ $acara->tarikh?->translatedFormat('d M Y') ?? '-' }}</p>
                                        <p class="info-note">{{ $acara->waktu_mula }} &ndash; {{ $acara->waktu_tamat }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="attendance-box">
                        <h3 class="attendance-title">Imbas kod QR untuk merekod kehadiran</h3>
                        <p class="attendance-desc">Selepas imbasan dibuat, masukkan nombor kad pengenalan untuk melengkapkan proses kehadiran.</p>

                        <table class="qr-table">
                            <tr>
                                <td class="qr-left">
                                    <div class="qr-frame">
                                        <table class="qr-frame-inner">
                                            <tr>
                                                <td>
                                                    <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="Kod QR" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td class="qr-right">
                                    <p class="scan-label">Pautan Kehadiran</p>
                                    <p class="scan-url">{{ $acara->publicUrl() }}</p>
                                    <p class="scan-help">Masukkan No. Kad Pengenalan selepas mengimbas.</p>
                                    <p class="scan-validity">Kehadiran dibuka sehingga {{ $acara->expires_at->translatedFormat('d M Y, H:i') }}.</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <table class="footer-table">
                        <tr>
                            <td class="footer-left">
                                <p class="generated-note">Poster dijana secara automatik oleh Sistem BAKIS <span class="muted-separator">&bull;</span> {{ now()->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="footer-right">
                                @if ($acara->isOpen())
                                    <span class="status-pill open">Kehadiran Dibuka</span>
                                @else
                                    <span class="status-pill closed">Kehadiran Ditutup</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
