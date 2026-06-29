<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat — {{ $sertifikat->nomor_sertifikat }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e8e8e8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 30px 20px;
        }

        /* ── Cert Wrapper ── */
        .cert-wrapper {
            width: 900px;
            min-height: 620px;
            background: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.2);
        }

        /* ── Decorative: Orange Big Circle (bottom-right) ── */
        .deco-circle-orange {
            position: absolute;
            bottom: -100px;
            right: -80px;
            width: 340px;
            height: 340px;
            background: #F4930B;
            border-radius: 50%;
            z-index: 0;
        }

        /* ── Decorative: diamond shapes (top-right) ── */
        .deco-diamonds {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 190px;
            height: 190px;
            z-index: 0;
        }

        .diamond {
            position: absolute;
            border-radius: 8px;
            transform: rotate(45deg);
        }

        .d1 {
            width: 55px;
            height: 55px;
            background: #3BA935;
            top: 10px;
            right: 60px;
        }

        .d2 {
            width: 55px;
            height: 55px;
            background: #3BA935;
            top: 10px;
            right: 0px;
            opacity: 0.7;
        }

        .d3 {
            width: 55px;
            height: 55px;
            background: #F4930B;
            top: 72px;
            right: 30px;
        }

        .d4 {
            width: 55px;
            height: 55px;
            background: #F4930B;
            top: 72px;
            right: 92px;
            opacity: 0.5;
        }

        /* ── Decorative: green small diamonds (bottom-left) ── */
        .deco-diamonds-bl {
            position: absolute;
            bottom: 10px;
            left: -20px;
            z-index: 1;
            display: flex;
            gap: 8px;
        }

        .d-sm {
            width: 38px;
            height: 38px;
            background: #3BA935;
            border-radius: 5px;
            transform: rotate(45deg);
        }

        .d-sm.orange {
            background: #F4930B;
            opacity: 0.7;
        }

        /* ── Laptop Image ── */
        .laptop-img {
            position: absolute;
            top: 14px;
            right: 210px;
            width: 170px;
            z-index: 1;
        }

        /* ── Content Area ── */
        .cert-content {
            position: relative;
            z-index: 2;
            padding: 36px 52px 32px 52px;
        }

        /* ── Logo Row ── */
        .logo-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .logo-row img {
            height: 44px;
        }

        /* ── Title ── */
        .cert-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 22px;
            font-weight: 900;
            color: #111;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .cert-title span {
            color: #3BA935;
        }

        /* ── Subtitle ── */
        .cert-subtitle {
            font-size: 12.5px;
            color: #555;
            margin-bottom: 10px;
        }

        /* ── Recipient Name ── */
        .recipient-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 40px;
            font-weight: 900;
            color: #3BA935;
            line-height: 1.15;
            margin-top: 6px;
            margin-bottom: 10px;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1.5px solid #222;
            margin-bottom: 14px;
            width: 100%;
            max-width: 520px;
        }

        /* ── Description ── */
        .cert-desc {
            font-size: 12.5px;
            color: #333;
            line-height: 1.75;
            max-width: 520px;
            margin-bottom: 14px;
        }

        /* ── Meta Info ── */
        .cert-meta {
            font-size: 12px;
            color: #333;
            line-height: 1.8;
            margin-bottom: 14px;
        }

        /* ── Appreciation ── */
        .cert-appreciation {
            font-size: 12.5px;
            color: #333;
            max-width: 560px;
            margin-bottom: 26px;
        }

        /* ── Footer ── */
        .cert-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            max-width: 580px;
        }

        /* Left: QR */
        .footer-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .footer-left-label {
            font-size: 11px;
            color: #333;
            font-weight: 500;
        }

        .qr-placeholder {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
        }

        /* Right: Signature */
        .footer-right {
            text-align: center;
        }

        .footer-date {
            font-size: 11.5px;
            color: #333;
            margin-bottom: 14px;
        }

        .signature-name {
            font-size: 13px;
            font-weight: 700;
            color: #111;
            border-top: 1.5px solid #222;
            padding-top: 6px;
            min-width: 170px;
        }

        .signature-title {
            font-size: 11px;
            font-style: italic;
            color: #444;
            margin-top: 2px;
        }

        /* Print */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .cert-wrapper {
                box-shadow: none;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    {{-- Print / Back Buttons --}}
    <div class="no-print" style="display:flex;gap:12px;margin-bottom:20px;">
        <a href="{{ route('peserta.sertifikat.index') }}"
            style="background:#fff;color:#333;border:1px solid #ccc;padding:10px 24px;border-radius:8px;font-size:13px;cursor:pointer;font-family:Inter,sans-serif;text-decoration:none;">
            ← Kembali
        </a>
        <button onclick="window.print()"
            style="background:#3BA935;color:white;border:none;padding:10px 28px;border-radius:8px;font-size:13px;cursor:pointer;font-family:Inter,sans-serif;">
            🖨 Cetak Sertifikat
        </button>
    </div>

    <div class="cert-wrapper">

        {{-- Decorative elements --}}
        <div class="deco-circle-orange"></div>

        <div class="deco-diamonds">
            <div class="diamond d1"></div>
            <div class="diamond d2"></div>
            <div class="diamond d3"></div>
            <div class="diamond d4"></div>
        </div>

        <div class="deco-diamonds-bl">
            <div class="d-sm"></div>
            <div class="d-sm orange"></div>
            <div class="d-sm" style="opacity:0.5;"></div>
        </div>

        {{-- Laptop Graduation Image --}}
        <img src="{{ asset('images/cert-laptop.png') }}" class="laptop-img" alt="">

        {{-- Main Content --}}
        <div class="cert-content">

            {{-- Logo --}}
            <div class="logo-row">
                <img src="{{ asset('images/logo/logo.svg') }}" alt="Edugenzi">
            </div>

            {{-- Title --}}
            <div class="cert-title">CERTIFICATE OF <span>COMPLETION</span></div>
            <div class="cert-subtitle">This certificate is proudly presented to</div>

            {{-- Name --}}
            <div class="recipient-name">{{ $sertifikat->peserta->nama_lengkap }}</div>

            <hr class="divider">

            {{-- Description --}}
            <p class="cert-desc">
                for successfully completing the <strong>{{ $sertifikat->nama_program }}</strong> program
                and fulfilling all learning requirements at Edugenzi.
            </p>

            {{-- Meta --}}
            <div class="cert-meta">
                Certificate Number: <strong>{{ $sertifikat->nomor_sertifikat }}</strong><br>
                Student ID (NIS): <strong>{{ $sertifikat->peserta->no_registrasi }}</strong>
            </div>

            {{-- Appreciation --}}
            <p class="cert-appreciation">
                We appreciate your hard work and wish you continued success in your future learning and achievements.
            </p>

            {{-- Footer --}}
            <div class="cert-footer">

                {{-- Left: QR Code --}}
                <div class="footer-left">
                    <span class="footer-left-label">Portfolio Verification:</span>
                    <div class="qr-placeholder">
                        {{-- QR Code SVG Pattern (decorative) --}}
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="80" height="80" fill="white" />
                            <!-- Top-left finder pattern -->
                            <rect x="4" y="4" width="28" height="28" rx="2" fill="#111" />
                            <rect x="8" y="8" width="20" height="20" rx="1" fill="white" />
                            <rect x="12" y="12" width="12" height="12" rx="1" fill="#111" />
                            <!-- Top-right finder pattern -->
                            <rect x="48" y="4" width="28" height="28" rx="2" fill="#111" />
                            <rect x="52" y="8" width="20" height="20" rx="1" fill="white" />
                            <rect x="56" y="12" width="12" height="12" rx="1" fill="#111" />
                            <!-- Bottom-left finder pattern -->
                            <rect x="4" y="48" width="28" height="28" rx="2" fill="#111" />
                            <rect x="8" y="52" width="20" height="20" rx="1" fill="white" />
                            <rect x="12" y="56" width="12" height="12" rx="1" fill="#111" />
                            <!-- Data modules (decorative) -->
                            <rect x="36" y="4" width="6" height="6" fill="#111" />
                            <rect x="36" y="14" width="6" height="6" fill="#111" />
                            <rect x="4" y="36" width="6" height="6" fill="#111" />
                            <rect x="14" y="36" width="6" height="6" fill="#111" />
                            <rect x="36" y="36" width="6" height="6" fill="#111" />
                            <rect x="46" y="36" width="6" height="6" fill="#111" />
                            <rect x="56" y="36" width="6" height="6" fill="#111" />
                            <rect x="66" y="36" width="6" height="6" fill="#111" />
                            <rect x="36" y="46" width="6" height="6" fill="#111" />
                            <rect x="56" y="46" width="6" height="6" fill="#111" />
                            <rect x="46" y="56" width="6" height="6" fill="#111" />
                            <rect x="66" y="56" width="6" height="6" fill="#111" />
                            <rect x="36" y="66" width="6" height="6" fill="#111" />
                            <rect x="56" y="66" width="6" height="6" fill="#111" />
                            <rect x="66" y="46" width="6" height="6" fill="#111" />
                        </svg>
                    </div>
                </div>

                {{-- Right: Date & Signature --}}
                <div class="footer-right">
                    <div class="footer-date">
                        On the day of, {{ $sertifikat->tgl_terbit->translatedFormat('F j, Y') }}
                    </div>
                    <div style="height: 36px;"></div>
                    <div class="signature-name">Semoga Raharja Wijaya</div>
                    <div class="signature-title">Founder &amp; CEO Edugenzi Academy</div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>