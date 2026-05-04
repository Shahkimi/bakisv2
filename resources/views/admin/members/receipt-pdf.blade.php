<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resit Keahlian - {{ $member->no_ahli ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #ffffff;
            line-height: 1.5;
        }

        .page {
            width: 100%;
            min-height: 297mm;
            padding: 20mm 20mm;
            position: relative;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header-left .org-name {
            font-size: 20px;
            font-weight: 700;
            color: #1e1b4b;
            letter-spacing: -0.3px;
        }

        .header-left .org-sub {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-right {
            text-align: right;
        }

        .receipt-badge {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-number {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
        }

        .receipt-number span {
            font-weight: 600;
            color: #1f2937;
        }

        .receipt-date {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 3px;
        }

        /* ── Status Banner ── */
        .status-banner {
            border-radius: 10px;
            padding: 10px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-banner.aktif {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
        }

        .status-banner.tidak_aktif {
            background: #f9fafb;
            border-left: 4px solid #9ca3af;
        }

        .status-banner.meninggal {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-dot.aktif { background: #10b981; }
        .status-dot.tidak_aktif { background: #9ca3af; }
        .status-dot.meninggal { background: #ef4444; }

        .status-text {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-text.aktif { color: #065f46; }
        .status-text.tidak_aktif { color: #374151; }
        .status-text.meninggal { color: #991b1b; }

        .status-label {
            font-size: 10px;
            color: #6b7280;
        }

        /* ── Section Titles ── */
        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* ── Info Blocks ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
            margin-bottom: 22px;
        }

        .info-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px 20px;
            margin-bottom: 22px;
        }

        .info-item label {
            display: block;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        .info-item .value {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .info-item .value.mono {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            letter-spacing: 0.5px;
        }

        /* ── Highlight Card (Member ID) ── */
        .member-card {
            background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
            border: 1px solid #c7d2fe;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .member-card-left .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #6366f1;
            font-weight: 600;
        }

        .member-card-left .name {
            font-size: 18px;
            font-weight: 800;
            color: #1e1b4b;
            margin-top: 2px;
            letter-spacing: -0.3px;
        }

        .member-card-left .ic {
            font-size: 11px;
            color: #4f46e5;
            margin-top: 3px;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .member-card-right {
            text-align: right;
        }

        .member-card-right .no-ahli-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #6366f1;
            font-weight: 600;
        }

        .member-card-right .no-ahli {
            font-size: 22px;
            font-weight: 800;
            color: #4f46e5;
            font-family: 'DejaVu Sans Mono', monospace;
            letter-spacing: 1px;
        }

        /* ── Payment Table ── */
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 11px;
        }

        .payment-table thead tr {
            background: #4f46e5;
            color: #ffffff;
        }

        .payment-table thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .payment-table thead th:last-child {
            text-align: right;
        }

        .payment-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .payment-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .payment-table tbody td {
            padding: 8px 12px;
            color: #374151;
        }

        .payment-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ── Payment Summary ── */
        .payment-summary {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 22px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
            font-size: 11px;
            color: #6b7280;
        }

        .summary-row.total {
            padding-top: 8px;
            margin-top: 4px;
            border-top: 2px solid #e5e7eb;
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .summary-row .amount {
            font-weight: 600;
            color: #111827;
        }

        .summary-row.total .amount {
            color: #4f46e5;
            font-size: 15px;
        }

        /* ── No Payment State ── */
        .no-payment {
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            color: #9ca3af;
            font-size: 11px;
            margin-bottom: 22px;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 30px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-left .generated {
            font-size: 9px;
            color: #9ca3af;
        }

        .footer-left .note {
            font-size: 8px;
            color: #d1d5db;
            margin-top: 2px;
        }

        .footer-right {
            text-align: right;
        }

        .signature-line {
            width: 140px;
            border-top: 1px solid #374151;
            margin-left: auto;
            margin-bottom: 4px;
        }

        .footer-right .sig-label {
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }

        .footer-right .sig-org {
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
        }

        /* ── Watermark ── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            font-weight: 900;
            color: rgba(79, 70, 229, 0.04);
            text-transform: uppercase;
            letter-spacing: 8px;
            pointer-events: none;
            white-space: nowrap;
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 16px 0;
        }

        .highlight-box {
            background: #eff6ff;
            border-left: 3px solid #3b82f6;
            border-radius: 0 6px 6px 0;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10px;
            color: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="watermark">RASMI</div>

<div class="page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            <div class="org-name">BAKIS</div>
            <div class="org-sub">Sistem Pengurusan Keahlian</div>
        </div>
        <div class="header-right">
            <div class="receipt-badge">Resit Keahlian</div>
            <div class="receipt-number">
                No. Resit: <span>{{ str_pad($member->id ?? 0, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</span>
            </div>
            <div class="receipt-date">Dijana pada: {{ now()->format('d M Y, H:i A') }}</div>
        </div>
    </div>

    {{-- ── STATUS BANNER ── --}}
    @php
        $statusCode = $member->memberStatus?->code ?? 'tidak_aktif';
        $statusName = $member->memberStatus?->name ?? 'Tidak Aktif';
        $approvedPayments = $member->payments->where('status', \App\Models\Payment::STATUS_APPROVED);
        $totalPaid = $approvedPayments->sum('jumlah');
    @endphp
    <div class="status-banner {{ $statusCode }}">
        <div class="status-dot {{ $statusCode }}"></div>
        <div>
            <div class="status-text {{ $statusCode }}">STATUS: {{ strtoupper($statusName) }}</div>
            <div class="status-label">Status semasa keanggotaan ahli</div>
        </div>
    </div>

    {{-- ── MEMBER IDENTITY CARD ── --}}
    <div class="member-card">
        <div class="member-card-left">
            <div class="label">Nama Ahli</div>
            <div class="name">{{ $member->nama }}</div>
            <div class="ic">No. KP: {{ $member->no_kp }}</div>
        </div>
        <div class="member-card-right">
            <div class="no-ahli-label">No. Ahli</div>
            <div class="no-ahli">{{ $member->no_ahli ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- ── MAKLUMAT KEANGGOTAAN ── --}}
    <div class="section-title">Maklumat Keanggotaan</div>
    <div class="info-grid-3">
        <div class="info-item">
            <label>Jabatan</label>
            <div class="value">{{ $member->jabatan?->nama_jabatan ?? '—' }}</div>
        </div>
        <div class="info-item">
            <label>Jawatan / Unit</label>
            <div class="value">{{ $member->jawatan?->nama_jawatan ?? '—' }}</div>
        </div>
        <div class="info-item">
            <label>Tarikh Daftar</label>
            <div class="value">{{ $member->tarikh_daftar?->format('d M Y') ?? '—' }}</div>
        </div>
    </div>

    {{-- ── MAKLUMAT PERIBADI ── --}}
    <div class="section-title">Maklumat Peribadi</div>
    <div class="info-grid">
        <div class="info-item">
            <label>Jantina</label>
            <div class="value">{{ $member->jantina === 'L' ? 'Lelaki' : ($member->jantina === 'P' ? 'Perempuan' : '—') }}</div>
        </div>
        <div class="info-item">
            <label>No. H/P</label>
            <div class="value mono">{{ $member->no_hp ?? '—' }}</div>
        </div>
        <div class="info-item">
            <label>Email</label>
            <div class="value">{{ $member->email ?? '—' }}</div>
        </div>
        <div class="info-item">
            <label>No. Telefon</label>
            <div class="value mono">{{ $member->no_tel ?? '—' }}</div>
        </div>
    </div>

    {{-- ── MAKLUMAT ALAMAT ── --}}
    <div class="section-title">Maklumat Alamat</div>
    <div class="info-grid">
        <div class="info-item">
            <label>Alamat</label>
            <div class="value">{{ $member->alamat1 ?? '—' }}{{ $member->alamat2 ? ', ' . $member->alamat2 : '' }}</div>
        </div>
        <div class="info-item">
            <label>Poskod / Bandar</label>
            <div class="value">{{ $member->poskod ?? '—' }} {{ $member->bandar ?? '' }}</div>
        </div>
        <div class="info-item">
            <label>Negeri</label>
            <div class="value">{{ $member->negeri ?? '—' }}</div>
        </div>
    </div>

    @if($member->catatan)
    <div class="highlight-box">
        <strong>Catatan:</strong> {{ $member->catatan }}
    </div>
    @endif

    <div class="divider"></div>

    {{-- ── SEJARAH PEMBAYARAN ── --}}
    <div class="section-title">Sejarah Pembayaran (Disahkan)</div>

    @if($approvedPayments->isEmpty())
        <div class="no-payment">
            Tiada rekod pembayaran yang telah disahkan.
        </div>
    @else
        <table class="payment-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tahun Bayar</th>
                    <th>Liputan Tahun</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Jumlah (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedPayments as $i => $payment)
                @php
                    $start = $payment->tahun_mula ?? $payment->tahun_bayar;
                    $end   = $payment->tahun_tamat ?? $payment->tahun_bayar;
                    $coverage = ($start == $end) ? (string)$start : $start . ' – ' . $end;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $payment->tahun_bayar }}</td>
                    <td>{{ $coverage }}</td>
                    <td>
                        <span class="badge badge-blue">
                            {{ match($payment->jenis ?? '') {
                                'pendaftaran_baru' => 'Pendaftaran Baru',
                                'pembaharuan'      => 'Pembaharuan',
                                default            => 'N/A'
                            } }}
                        </span>
                    </td>
                    <td><span class="badge badge-green">Disahkan</span></td>
                    <td>{{ number_format((float)($payment->jumlah ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="payment-summary">
            <div class="summary-row">
                <span>Jumlah Rekod</span>
                <span class="amount">{{ $approvedPayments->count() }} rekod</span>
            </div>
            <div class="summary-row total">
                <span>JUMLAH KESELURUHAN</span>
                <span class="amount">RM {{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            <div class="generated">Dijana secara automatik oleh Sistem BAKIS</div>
            <div class="note">Dokumen ini adalah cetakan rasmi. Sah tanpa tandatangan sekiranya dicetak dari sistem.</div>
        </div>
        <div class="footer-right">
            <div class="signature-line"></div>
            <div class="sig-label">Pegawai Yang Bertanggungjawab</div>
            <div class="sig-org">BAKIS — Sistem Pengurusan Keahlian</div>
        </div>
    </div>

</div>
</body>
</html>
