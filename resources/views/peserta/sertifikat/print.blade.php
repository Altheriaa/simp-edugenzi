<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat — {{ $sertifikat->nomor_sertifikat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f5f0eb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }
        .cert-wrapper {
            width: 794px;
            background: #fff;
            border: 1px solid #d4c5b0;
            box-shadow: 0 10px 60px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }
        /* Ornamental border */
        .cert-wrapper::before {
            content: '';
            position: absolute;
            inset: 12px;
            border: 2px solid #b8860b;
            pointer-events: none;
            z-index: 1;
        }
        .cert-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 36px 60px 28px;
            text-align: center;
            position: relative;
        }
        .cert-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #b8860b, #ffd700, #b8860b);
        }
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,215,0,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,215,0,0.4);
        }
        .org-name {
            font-family: 'Arial', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #ffd700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .org-sub {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .cert-title {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .cert-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .cert-body {
            padding: 40px 60px;
            text-align: center;
        }
        .presented-to {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #666;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .recipient-name {
            font-size: 42px;
            font-weight: 400;
            color: #1a1a2e;
            font-style: italic;
            border-bottom: 2px solid #b8860b;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .cert-desc {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #444;
            line-height: 1.8;
            max-width: 560px;
            margin: 0 auto;
        }
        .program-name {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a2e;
            font-style: italic;
        }
        .predikat-badge {
            display: inline-block;
            margin-top: 16px;
            padding: 6px 24px;
            background: linear-gradient(135deg, #b8860b, #ffd700, #b8860b);
            color: #1a1a2e;
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 2px;
        }
        .cert-footer {
            padding: 20px 60px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .footer-item {
            text-align: center;
        }
        .footer-label {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #888;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .footer-value {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
            border-top: 1px solid #b8860b;
            padding-top: 8px;
        }
        .footer-value-mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-family: 'Arial', sans-serif;
            font-size: 100px;
            font-weight: 900;
            color: rgba(0,0,0,0.025);
            letter-spacing: 10px;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }
        @media print {
            body { background: white; padding: 0; }
            .cert-wrapper { box-shadow: none; border: none; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div>
        {{-- Print Button --}}
        <div class="no-print" style="text-align:center;margin-bottom:20px;">
            <button onclick="window.print()"
                    style="background:#1a1a2e;color:white;border:none;padding:10px 28px;border-radius:6px;font-size:14px;cursor:pointer;font-family:Arial,sans-serif;">
                🖨 Cetak Sertifikat
            </button>
        </div>

        <div class="cert-wrapper">
            <div class="watermark">EDUGENZI</div>

            {{-- Header --}}
            <div class="cert-header">
                <div class="logo-area">
                    <div class="logo-icon">
                        <svg width="24" height="24" fill="none" stroke="#ffd700" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="org-name">Edugenzi</div>
                        <div class="org-sub">Banda Aceh</div>
                    </div>
                </div>
                <div class="cert-title">Sertifikat</div>
                <div class="cert-subtitle">Penghargaan Kelulusan Program</div>
            </div>

            {{-- Body --}}
            <div class="cert-body">
                <div class="presented-to">Diberikan kepada</div>
                <div class="recipient-name">{{ $sertifikat->peserta->nama_lengkap }}</div>
                <div class="cert-desc">
                    atas keberhasilan menyelesaikan program
                </div>
                <div class="cert-desc program-name" style="margin-top:8px;">
                    "{{ $sertifikat->nama_program }}"
                </div>
                <div class="cert-desc" style="margin-top:12px;">
                    yang diselenggarakan oleh Edugenzi Banda Aceh.
                </div>
                <div>
                    <span class="predikat-badge">{{ $sertifikat->predikat }}</span>
                </div>
            </div>

            {{-- Footer --}}
            <div class="cert-footer">
                <div class="footer-item">
                    <div class="footer-label">Nomor Sertifikat</div>
                    <div class="footer-value footer-value-mono">{{ $sertifikat->nomor_sertifikat }}</div>
                </div>
                <div class="footer-item">
                    <div class="footer-label">Tanggal Terbit</div>
                    <div class="footer-value">{{ $sertifikat->tgl_terbit->format('d F Y') }}</div>
                </div>
            </div>

            <div style="padding:0 60px 30px;text-align:right;">
                <div style="display:inline-block;text-align:center;">
                    <div style="height:60px;margin-bottom:8px;"></div>
                    <div style="border-top:1px solid #b8860b;padding-top:8px;font-family:Arial,sans-serif;font-size:12px;font-weight:600;color:#1a1a2e;">
                        {{ $sertifikat->mentor->nama_lengkap }}
                    </div>
                    <div style="font-family:Arial,sans-serif;font-size:10px;color:#888;letter-spacing:2px;text-transform:uppercase;margin-top:2px;">Mentor</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
