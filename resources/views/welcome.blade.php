<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status BLT Dana Desa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-dark:   #1243a8;
            --blue-mid:    #2563eb;
            --blue-pale:   #eff6ff;
            --blue-border: #bfdbfe;
            --gold:        #f59e0b;
            --gold-light:  #fde68a;
            --white:       #ffffff;
            --bg:          #f0f4ff;
            --gray-100:    #f4f6fb;
            --gray-300:    #cbd5e1;
            --gray-500:    #94a3b8;
            --gray-600:    #64748b;
            --gray-800:    #1e293b;
            --red:         #dc2626;
            --green:       #16a34a;
            --shadow-sm:   0 2px 8px rgba(37,99,235,.08);
            --shadow-md:   0 6px 24px rgba(37,99,235,.13);
            --shadow-lg:   0 12px 48px rgba(37,99,235,.18);
            --radius:      16px;
            --radius-sm:   10px;
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--gray-800); line-height: 1.6; }

        /* ── NAVBAR ── */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 24px;
            background: rgba(18,67,168,.97);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 16px rgba(18,67,168,.35);
            gap: 12px;
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; min-width: 0; flex: 1; }
        .navbar-brand-logo { height: 38px; width: 38px; border-radius: 50%; background: var(--gold); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .navbar-title { color: var(--white); min-width: 0; }
        .navbar-title strong { display: block; font-size: 14px; font-weight: 700; line-height: 1.3; }
        .navbar-title span { font-size: 11px; opacity: .7; }
        .btn-login {
            flex-shrink: 0;
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 20px; border-radius: 50px;
            background: var(--gold); color: #78350f;
            font-weight: 700; font-size: 13px; text-decoration: none;
            transition: background .2s, transform .15s;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }
        .btn-login:hover { background: var(--gold-light); transform: translateY(-1px); }
        @media (max-width: 480px) {
            .navbar { padding: 10px 14px; }
            .navbar-title span { display: none; }
            .navbar-title strong { font-size: 12px; }
            .btn-login { padding: 8px 14px; font-size: 12px; }
        }

        /* ── HERO ── */
        .hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 65%, #1d4ed8 100%); padding: 72px 32px 88px; text-align: center; }
        .hero::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
        .hero-badge { display: inline-block; margin-bottom: 16px; padding: 5px 16px; border-radius: 50px; background: rgba(245,158,11,.2); border: 1px solid rgba(245,158,11,.5); color: var(--gold-light); font-size: 12px; font-weight: 600; letter-spacing: 1px; }
        .hero h1 { font-family: 'Lora', serif; font-size: clamp(26px, 5vw, 48px); font-weight: 500; color: var(--white); line-height: 1.25; margin-bottom: 14px; }
        .hero h1 em { font-style: italic; color: var(--gold-light); }
        .hero p { max-width: 560px; margin: 0 auto 36px; color: rgba(255,255,255,.82); font-size: 15px; }
        .hero-scroll-hint { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.4); font-size: 12px; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .hero-scroll-hint svg { animation: bounce 1.8s ease infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(6px)} }

        /* ── SEARCH ── */
        .search-wrapper { max-width: 680px; margin: -48px auto 0; padding: 0 16px; position: relative; z-index: 10; }
        .search-card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-lg); padding: 32px; }
        .search-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--blue-dark); }
        .search-card p { font-size: 13px; color: var(--gray-600); margin-bottom: 20px; }
        .input-group { display: flex; gap: 10px; }
        .input-nik { flex: 1; padding: 13px 18px; border-radius: var(--radius-sm); border: 2px solid var(--gray-300); font-size: 15px; font-family: inherit; transition: border .2s, box-shadow .2s; outline: none; }
        .input-nik:focus { border-color: var(--blue-mid); box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .input-nik::placeholder { color: #aab; }
        .btn-cek { padding: 13px 28px; border-radius: var(--radius-sm); background: var(--blue-mid); color: var(--white); font-weight: 700; font-size: 14px; font-family: inherit; border: none; cursor: pointer; transition: background .2s, transform .15s; white-space: nowrap; }
        .btn-cek:hover { background: var(--blue-dark); transform: translateY(-1px); }

        /* ── RESULT ── */
        .result-wrapper { max-width: 680px; margin: 24px auto 0; padding: 0 16px; }
        .result-card { border-radius: var(--radius); padding: 28px; box-shadow: var(--shadow-md); }
        .result-card.layak     { background: #f0fdf4; border-left: 5px solid var(--green); }
        .result-card.tidak     { background: #fef2f2; border-left: 5px solid var(--red); }
        .result-card.pending   { background: #fffbeb; border-left: 5px solid var(--gold); }
        .result-card.not-found { background: var(--gray-100); border-left: 5px solid var(--gray-300); text-align: center; padding: 40px; }

        .result-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
        .result-icon { font-size: 36px; }
        .result-name { font-size: 19px; font-weight: 700; }
        .result-nik { font-size: 12px; color: var(--gray-600); margin-top: 2px; }

        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; margin-bottom: 20px; }
        .status-badge.layak   { background: var(--green); color: var(--white); }
        .status-badge.tidak   { background: var(--red);   color: var(--white); }
        .status-badge.pending { background: var(--gold);  color: #78350f; }

        /* ── TRACKING ── */
        .tracking-timeline { display: flex; flex-direction: column; margin-bottom: 8px; }
        .track-step { display: flex; align-items: flex-start; gap: 14px; padding: 12px 0; position: relative; }
        .track-step:not(:last-child)::after { content: ''; position: absolute; left: 15px; top: 44px; width: 2px; height: calc(100% - 20px); background: var(--gray-300); }
        .track-step.done::after { background: var(--blue-mid); }
        .track-dot { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; border: 2px solid var(--gray-300); background: var(--white); color: var(--gray-500); position: relative; z-index: 1; }
        .track-step.done   .track-dot { border-color: var(--blue-mid); background: var(--blue-mid); color: var(--white); }
        .track-step.active .track-dot { border-color: var(--gold); background: var(--gold); color: #78350f; }
        .track-info { padding-top: 4px; }
        .track-label { font-size: 14px; font-weight: 700; color: var(--gray-800); }
        .track-step.done   .track-label { color: var(--blue-mid); }
        .track-step.active .track-label { color: #92400e; }
        .track-desc { font-size: 12px; color: var(--gray-600); margin-top: 2px; }
        .track-notice { margin-top: 16px; padding: 14px 16px; border-radius: var(--radius-sm); background: #fffbeb; border: 1px solid #fde68a; font-size: 13px; color: #78350f; line-height: 1.6; }

        /* ── INFO & DATA GRID ── */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
        .info-item { background: rgba(255,255,255,.7); border-radius: var(--radius-sm); padding: 10px 14px; }
        .info-label { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--gray-600); margin-bottom: 2px; font-weight: 600; }
        .info-value { font-size: 14px; font-weight: 600; color: var(--gray-800); }
        .span2 { grid-column: span 2; }

        /* ── EXPLANATION ── */
        .explanation-section { margin-top: 18px; display: flex; flex-direction: column; gap: 12px; }
        .expl-block { background: rgba(255,255,255,.6); border-radius: var(--radius-sm); padding: 14px 16px; }
        .expl-title { font-size: 11px; font-weight: 700; margin-bottom: 10px; color: var(--gray-800); text-transform: uppercase; letter-spacing: .6px; display: flex; align-items: center; gap: 7px; }
        .expl-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .data-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .data-mini-item { background: rgba(255,255,255,.75); border-radius: 8px; padding: 9px 12px; }
        .explanation-list { list-style: none; display: flex; flex-direction: column; gap: 6px; }
        .explanation-list li { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; padding: 8px 12px; border-radius: 8px; }
        .expl-positive { background: rgba(22,163,74,.1);  color: #14532d; }
        .expl-negative { background: rgba(220,38,38,.1);  color: #7f1d1d; }
        .explanation-list li .icon { flex-shrink: 0; font-size: 14px; }
        .expl-empty { font-size: 12px; color: var(--gray-600); font-style: italic; }
        .summary-box { padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(255,255,255,.85); font-size: 13px; line-height: 1.6; border-left: 3px solid var(--gold); }

        /* ── SKOR BAR ── */
        .skor-bar-wrap { background: rgba(255,255,255,.6); border-radius: var(--radius-sm); padding: 14px 16px; }
        .skor-bar-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--gray-600); margin-bottom: 8px; font-weight: 600; }
        .skor-bar-track { height: 8px; background: rgba(0,0,0,.08); border-radius: 99px; overflow: hidden; }
        .skor-bar-fill { height: 100%; border-radius: 99px; }
        .skor-bar-fill.layak { background: linear-gradient(90deg, #4ade80, var(--green)); }
        .skor-bar-fill.tidak { background: linear-gradient(90deg, #fca5a5, var(--red)); }
        .skor-bar-note { font-size: 11px; color: var(--gray-600); margin-top: 5px; }

        /* ── DIVIDER ── */
        .section-divider { border: none; border-top: 1px solid var(--blue-border); margin: 20px 0 16px; }

        /* ── SECTIONS ── */
        .section { max-width: 1100px; margin: 64px auto; padding: 0 24px; }
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-label { display: inline-block; padding: 4px 14px; border-radius: 50px; background: var(--blue-pale); color: var(--blue-mid); font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px; border: 1px solid var(--blue-border); }
        .section-header h2 { font-size: clamp(22px, 3.5vw, 32px); font-weight: 800; color: var(--blue-dark); }
        .section-header p { font-size: 14px; color: var(--gray-600); max-width: 500px; margin: 8px auto 0; }

        /* ── STEPS ── */
        .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .step-card { background: var(--white); border-radius: var(--radius); padding: 28px 24px; box-shadow: var(--shadow-sm); text-align: center; transition: transform .2s, box-shadow .2s; }
        .step-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        .step-num { width: 48px; height: 48px; border-radius: 50%; margin: 0 auto 14px; background: var(--blue-mid); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; }
        .step-icon { font-size: 30px; margin-bottom: 10px; }
        .step-card h3 { font-size: 15px; font-weight: 700; margin-bottom: 6px; color: var(--blue-dark); }
        .step-card p { font-size: 13px; color: var(--gray-600); }

        /* ── LAPORAN ── */
        .laporan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .laporan-card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; transition: transform .2s, box-shadow .2s; }
        .laporan-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        .laporan-thumb { height: 160px; background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); display: flex; align-items: center; justify-content: center; font-size: 48px; }
        .laporan-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .laporan-body { padding: 18px; }
        .laporan-date { font-size: 11px; color: var(--gray-600); margin-bottom: 6px; }
        .laporan-title { font-size: 15px; font-weight: 700; margin-bottom: 8px; color: var(--blue-dark); }
        .laporan-desc { font-size: 13px; color: var(--gray-600); margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .btn-unduh { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 50px; background: var(--blue-mid); color: var(--white); font-size: 12px; font-weight: 700; text-decoration: none; transition: background .2s; }
        .btn-unduh:hover { background: var(--blue-dark); }

        /* ── GALERI ── */
        .galeri-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
        .galeri-item { border-radius: var(--radius-sm); overflow: hidden; aspect-ratio: 4/3; box-shadow: var(--shadow-sm); cursor: pointer; transition: transform .2s, box-shadow .2s; position: relative; }
        .galeri-item:hover { transform: scale(1.03); box-shadow: var(--shadow-md); }
        .galeri-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .galeri-item::after { content: attr(data-caption); position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(18,67,168,.85)); color: white; font-size: 11px; font-weight: 600; padding: 20px 10px 8px; opacity: 0; transition: opacity .2s; }
        .galeri-item:hover::after { opacity: 1; }
        .galeri-placeholder { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--blue-pale), var(--blue-border)); color: var(--blue-mid); font-size: 28px; }
        .galeri-placeholder span { font-size: 11px; margin-top: 4px; font-weight: 600; }

        .empty-state { text-align: center; padding: 60px 20px; color: var(--gray-600); }
        .empty-state .emoji { font-size: 48px; margin-bottom: 12px; display: block; }

        /* ── CTA ── */
        .cta-strip { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); padding: 60px 24px; text-align: center; margin-top: 64px; }
        .cta-strip h2 { font-size: 26px; font-weight: 800; color: var(--white); margin-bottom: 10px; }
        .cta-strip p { color: rgba(255,255,255,.8); font-size: 14px; margin-bottom: 24px; max-width: 480px; margin-left: auto; margin-right: auto; }
        .btn-cta-big { display: inline-flex; align-items: center; gap: 8px; padding: 14px 36px; border-radius: 50px; background: var(--white); color: var(--blue-mid); font-weight: 800; font-size: 15px; border: none; cursor: pointer; font-family: inherit; transition: background .2s, transform .15s; text-decoration: none; }
        .btn-cta-big:hover { background: var(--gold-light); transform: translateY(-2px); }

        footer { background: var(--blue-dark); color: rgba(255,255,255,.6); text-align: center; padding: 24px; font-size: 12px; }
        footer strong { color: var(--white); }

        .fade-up { opacity: 0; transform: translateY(24px); animation: fadeUp .6s ease forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }

        @media (max-width: 600px) {
            .hero { padding: 52px 16px 72px; }
            .search-card { padding: 22px 18px; }
            .input-group { flex-direction: column; }
            .info-grid, .data-mini-grid { grid-template-columns: 1fr; }
            .span2 { grid-column: span 1; }
            .section { margin: 48px auto; padding: 0 16px; }
            .result-card { padding: 20px 16px; }
            .result-name { font-size: 16px; }
            .steps-grid { grid-template-columns: 1fr; }
            .laporan-grid { grid-template-columns: 1fr; }
            .galeri-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .cta-strip { padding: 40px 16px; }
            .cta-strip h2 { font-size: 20px; }
            .section-header h2 { font-size: 20px; }
        }
    </style>
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar">
    <a href="{{ url('/') }}" class="navbar-brand">
        <div class="navbar-brand-logo">🏡</div>
        <div class="navbar-title">
            <strong>BLT Dana Desa</strong>
            <span>Portal Informasi Publik</span>
        </div>
    </a>
    <a href="{{ route('login') }}" class="btn-login">🔐 Login </a>
</nav>

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-badge">BANTUAN LANGSUNG TUNAI – DANA DESA</div>
    <h1>Cek Status <em>Penerimaan BLT</em><br>Anda Sekarang</h1>
    <p>Masukkan NIK KTP Anda untuk mengetahui status kelayakan penerimaan Bantuan Langsung Tunai Dana Desa secara transparan dan akuntabel.</p>
    <div class="hero-scroll-hint">
        <span>Scroll ke bawah</span>
        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
    </div>
</section>

{{-- ══ SEARCH ══ --}}
<div class="search-wrapper fade-up">
    <div class="search-card">
        <h2>🔍 Cek Status Penerimaan BLT</h2>
        <p>Masukkan Nomor Induk Kependudukan (NIK) yang tertera pada KTP Anda — 16 digit angka.</p>
        <form method="GET" action="{{ url('/') }}">
            <div class="input-group">
                <input
                    type="text" name="nik" class="input-nik"
                    placeholder="Contoh: 3512XXXXXXXXXXXX"
                    value="{{ $nik }}" maxlength="16" inputmode="numeric"
                    pattern="\d{16}" title="NIK harus 16 digit angka"
                    aria-label="Nomor Induk Kependudukan"
                >
                <button type="submit" class="btn-cek">Cek Sekarang</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ RESULT ══ --}}
@if($nik !== '')
<div class="result-wrapper fade-up delay-1">
    @if($statusData)
        @php
            $pred   = $statusData->prediksiKelayakan;
            $final  = $penerimaFinal ?? null;   // dari controller, bukan relasi langsung

            // Sudah ditetapkan jika ada record di penerima_finals
            $sudahDitetapkan = !is_null($final);

            // Status layak/tidak dari prediksi
            $rekStr  = strtolower($pred->recommendation ?? $pred->status ?? $pred->rekomendasi ?? '');
            $isLayak = $pred && in_array($rekStr, ['layak', 'ya', 'yes', '1', 'true']);

            // Kalau ada field status di PenerimaFinal, override
            if ($sudahDitetapkan && isset($final->status)) {
                $isLayak = in_array(strtolower($final->status), ['layak', 'diterima', 'ya', '1']);
            }

            $cardClass = $sudahDitetapkan ? ($isLayak ? 'layak' : 'tidak') : 'pending';
            $icon      = $sudahDitetapkan ? ($isLayak ? '✅' : '❌') : '⏳';
            $statusStr = $isLayak ? 'LAYAK' : 'TIDAK LAYAK';

            // Probabilitas (0–100)
            $probRaw = (float)($pred->probability ?? $pred->prob ?? $pred->skor_kelayakan ?? 0);
            $probPct = min(100, max(0, $probRaw > 1 ? $probRaw : $probRaw * 100));

            $hasExpl = !empty($explanation['positive']) || !empty($explanation['negative']) || !empty($explanation['summary']);

            // Usia dari tanggal_lahir jika field usia kosong
            $usia = $statusData->usia ?? ($statusData->tanggal_lahir
                ? \Carbon\Carbon::parse($statusData->tanggal_lahir)->age
                : null);
        @endphp

        <div class="result-card {{ $cardClass }}">

            {{-- Header identitas --}}
            <div class="result-header">
                <span class="result-icon">{{ $icon }}</span>
                <div>
                    <div class="result-name">{{ $statusData->nama_lengkap ?? 'Nama tidak tersedia' }}</div>
                    <div class="result-nik">NIK: {{ $statusData->nik }}</div>
                </div>
            </div>

            {{-- Status badge --}}
            @if($sudahDitetapkan)
                <span class="status-badge {{ $cardClass }}">
                    {{ $isLayak ? '✔' : '✖' }} {{ $statusStr }} MENERIMA BLT-DD
                </span>
            @else
                <span class="status-badge pending">⏳ MENUNGGU PENETAPAN KELURAHAN</span>
            @endif

            {{-- ══════════════════════════════
                 SEBELUM PENETAPAN → Tracking
                 ══════════════════════════════ --}}
            @if(!$sudahDitetapkan)

            {{-- Info dasar yang tetap boleh ditampilkan --}}
            <div class="info-grid" style="margin-bottom:20px;">
                @if($statusData->rt)
                <div class="info-item">
                    <div class="info-label">RT</div>
                    <div class="info-value">RT {{ $statusData->rt->nama_rt ?? '-' }}</div>
                </div>
                @endif
                @if($statusData->rt?->dusun)
                <div class="info-item">
                    <div class="info-label">Dusun</div>
                    <div class="info-value">{{ $statusData->rt->dusun->nama_dusun ?? '-' }}</div>
                </div>
                @endif
            </div>

            <div class="tracking-timeline">
                <div class="track-step done">
                    <div class="track-dot">✓</div>
                    <div class="track-info">
                        <div class="track-label">Data Terdaftar</div>
                        <div class="track-desc">Data Anda telah tercatat dalam sistem desa</div>
                    </div>
                </div>
                <div class="track-step done">
                    <div class="track-dot">✓</div>
                    <div class="track-info">
                        <div class="track-label">Verifikasi & Analisis Sistem</div>
                        <div class="track-desc">Data telah diverifikasi dan dianalisis kelayakannya</div>
                    </div>
                </div>
                <div class="track-step active">
                    <div class="track-dot">→</div>
                    <div class="track-info">
                        <div class="track-label">Menunggu Penetapan Kelurahan</div>
                        <div class="track-desc">Pejabat kelurahan sedang melakukan penetapan penerima resmi</div>
                    </div>
                </div>
                <div class="track-step">
                    <div class="track-dot">4</div>
                    <div class="track-info">
                        <div class="track-label">Penetapan Resmi & Pengumuman</div>
                        <div class="track-desc">Hasil akhir dan detail penilaian akan ditampilkan di sini</div>
                    </div>
                </div>
            </div>

            <div class="track-notice">
                ℹ️ <strong>Detail belum tersedia.</strong>
                Hasil penilaian lengkap akan muncul setelah pihak kelurahan menyelesaikan proses penetapan resmi.
                Silakan cek kembali secara berkala atau tanyakan langsung ke kantor desa.
            </div>
{{-- ══════════════════════════════
     SETELAH PENETAPAN → Detail (REVISI: data sensitif disembunyikan)
     ══════════════════════════════ --}}
@else

{{-- Identitas dasar saja --}}
<div class="info-grid" style="margin-bottom:20px;">
    @if($statusData->rt)
    <div class="info-item">
        <div class="info-label">RT</div>
        <div class="info-value">RT {{ str_pad($statusData->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}</div>
    </div>
    @endif
    @if($statusData->rt?->dusun)
    <div class="info-item">
        <div class="info-label">Dusun</div>
        <div class="info-value">{{ $statusData->rt->dusun->nama_dusun ?? '-' }}</div>
    </div>
    @endif
    @if($statusData->jenis_kelamin)
    <div class="info-item">
        <div class="info-label">Jenis Kelamin</div>
        <div class="info-value">{{ $statusData->jenis_kelamin }}</div>
    </div>
    @endif
    @if($usia)
    <div class="info-item">
        <div class="info-label">Usia</div>
        <div class="info-value">{{ $usia }} tahun</div>
    </div>
    @endif
    @if($statusData->alamat)
    <div class="info-item span2">
        <div class="info-label">Alamat</div>
        <div class="info-value">{{ $statusData->alamat }}</div>
    </div>
    @endif
</div>

{{-- Probabilitas bar — ditampilkan tapi tanpa label angka detail --}}
@if($probPct > 0)
<div class="skor-bar-wrap" style="margin-bottom:20px;">
    <div class="skor-bar-label">
        <span>Tingkat Kelayakan</span>
        <strong>{{ $isLayak ? 'Memenuhi Syarat' : 'Tidak Memenuhi Syarat' }}</strong>
    </div>
    <div class="skor-bar-track">
        <div class="skor-bar-fill {{ $isLayak ? 'layak' : 'tidak' }}" style="width:{{ $probPct }}%"></div>
    </div>
    <div class="skor-bar-note" style="color:var(--gray-500);font-style:italic;">
        Penilaian dilakukan oleh sistem analisis berdasarkan data yang tercatat.
    </div>
</div>
@endif

{{-- Ringkasan Keputusan --}}
<div class="expl-block">
    <div class="expl-title"><span class="expl-dot" style="background:var(--gold);"></span>Ringkasan Keputusan</div>
    <div class="summary-box">
        @if($isLayak)
            💬 Berdasarkan hasil verifikasi dan analisis data yang telah dilakukan oleh pihak kelurahan,
            <strong>{{ $statusData->nama_lengkap ?? 'warga ini' }}</strong>
            dinyatakan <strong>LAYAK</strong> menerima Bantuan Langsung Tunai Dana Desa (BLT-DD)
            pada periode ini.
            Untuk informasi lebih lanjut mengenai jadwal dan mekanisme penyaluran,
            silakan hubungi petugas desa atau RT setempat.
        @else
            💬 Berdasarkan hasil verifikasi dan analisis data yang telah dilakukan oleh pihak kelurahan,
            <strong>{{ $statusData->nama_lengkap ?? 'warga ini' }}</strong>
            dinyatakan <strong>TIDAK LAYAK</strong> menerima Bantuan Langsung Tunai Dana Desa (BLT-DD)
            pada periode ini.

            @if(!empty($faktNeg))
            <div style="margin-top:14px;">
                <div style="font-size:12px;font-weight:700;color:#dc2626;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block;flex-shrink:0;"></span>
                    FAKTOR PENGURANG / PENOLAK
                </div>
                @foreach($faktNeg as $item)
                <div style="display:flex;align-items:center;gap:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:9px 12px;margin-bottom:6px;font-size:12px;color:#b91c1c;font-weight:500;">
                    <span style="font-size:14px;flex-shrink:0;font-weight:700;">✕</span>
                    <span>{{ $item }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <div style="margin-top:12px;font-size:12px;color:#64748b;line-height:1.6;">
                Jika Anda merasa keputusan ini kurang tepat, silakan mengajukan keberatan
                atau klarifikasi langsung ke kantor desa dengan membawa dokumen pendukung.
            </div>
        @endif
    </div>
</div>

{{-- Tanggal penetapan --}}
@if($final && $final->created_at)
<div style="font-size:11px;color:var(--gray-500);padding:4px 2px;text-align:right;">
    Ditetapkan pada: <strong style="color:var(--gray-600);">{{ \Carbon\Carbon::parse($final->created_at)->translatedFormat('d F Y') }}</strong>
</div>
@endif

{{-- Catatan privasi --}}
<div style="margin-top:14px;padding:10px 14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;font-size:11px;color:#64748b;display:flex;align-items:flex-start;gap:8px;">
    <span style="font-size:14px;">🔒</span>
    <span>Detail penilaian bersifat rahasia dan hanya dapat diakses oleh petugas kelurahan yang berwenang. Kebijakan ini diterapkan untuk menjaga privasi dan keharmonisan antar warga.</span>
</div>

@endif{{-- /sudahDitetapkan --}}

        </div>{{-- /result-card --}}

    @else
        <div class="result-card not-found">
            <div style="font-size:52px;margin-bottom:12px;">🔎</div>
            <div style="font-size:17px;font-weight:700;margin-bottom:8px;">Data Tidak Ditemukan</div>
            <p style="color:var(--gray-600);font-size:14px;">
                NIK <strong>{{ $nik }}</strong> tidak terdaftar dalam sistem.<br>
                Pastikan NIK sudah benar, atau hubungi kantor desa setempat.
            </p>
        </div>
    @endif
</div>
@endif

{{-- ══ CARA CEK ══ --}}
<section class="section" id="cara-cek">
    <div class="section-header">
        <div class="section-label">PANDUAN</div>
        <h2>Cara Mengecek Status BLT</h2>
        <p>Mudah dan cepat — tidak perlu login atau mendaftar.</p>
    </div>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-icon">🪪</div>
            <div class="step-num">1</div>
            <h3>Siapkan KTP Anda</h3>
            <p>Ambil KTP dan lihat Nomor Induk Kependudukan (NIK) — 16 digit angka yang unik tiap warga.</p>
        </div>
        <div class="step-card">
            <div class="step-icon">⌨️</div>
            <div class="step-num">2</div>
            <h3>Ketik NIK di Pencarian</h3>
            <p>Masukkan 16 digit NIK pada kolom pencarian di atas. Pastikan tidak ada angka yang salah.</p>
        </div>
        <div class="step-card">
            <div class="step-icon">🖱️</div>
            <div class="step-num">3</div>
            <h3>Tekan "Cek Sekarang"</h3>
            <p>Klik tombol Cek Sekarang. Status Anda akan langsung muncul di bawah form pencarian.</p>
        </div>
        <div class="step-card">
            <div class="step-icon">📋</div>
            <div class="step-num">4</div>
            <h3>Baca Hasil</h3>
            <p>Setelah penetapan kelurahan, detail penilaian dan faktor kelayakan ditampilkan secara lengkap.</p>
        </div>
    </div>
</section>

{{-- ══ LAPORAN PUBLIK ══ --}}
<section class="section" id="laporan">
    <div class="section-header">
        <div class="section-label">TRANSPARANSI</div>
        <h2>Laporan Publik</h2>
        <p>Dokumen laporan resmi yang diunggah pihak kelurahan untuk informasi warga.</p>
    </div>
    @if($laporanPubliks->isEmpty())
        <div class="empty-state">
            <span class="emoji">📄</span>
            <p>Belum ada laporan publik yang diunggah saat ini.<br>Silakan kembali lagi nanti.</p>
        </div>
    @else
        <div class="laporan-grid">
            @foreach($laporanPubliks as $laporan)
            <div class="laporan-card">
                <div class="laporan-thumb">
                    @if($laporan->thumbnail)
                        <img src="{{ asset('storage/' . $laporan->thumbnail) }}" alt="{{ $laporan->judul }}">
                    @else
                        📋
                    @endif
                </div>
                <div class="laporan-body">
                    <div class="laporan-date">📅 {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y') }}</div>
                    <div class="laporan-title">{{ $laporan->judul }}</div>
                    @if($laporan->deskripsi)
                    <div class="laporan-desc">{{ $laporan->deskripsi }}</div>
                    @endif
                    @if($laporan->file_path)
                    <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank" class="btn-unduh">⬇️ Unduh Laporan</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ══ GALERI ══ --}}
<section class="section" id="galeri">
    <div class="section-header">
        <div class="section-label">DOKUMENTASI</div>
        <h2>Galeri Kegiatan</h2>
        <p>Dokumentasi foto kegiatan penyaluran dan verifikasi BLT Dana Desa.</p>
    </div>
    @isset($galeris)
        @if($galeris->isNotEmpty())
        <div class="galeri-grid">
            @foreach($galeris as $foto)
            <div class="galeri-item">
                <img src="{{ asset('storage/' . $foto->file_path) }}" alt="{{ $foto->keterangan ?? 'Foto kegiatan' }}">
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state"><span class="emoji">🖼️</span><p>Belum ada foto galeri yang diunggah.</p></div>
        @endif
    @else
    <div class="empty-state"><span class="emoji">🖼️</span><p>Belum ada foto galeri yang diunggah.</p></div>
    @endisset
</section>

{{-- ══ CTA ══ --}}
<div class="cta-strip">
    <h2>Belum Tahu Status Anda?</h2>
    <p>Cek status penerimaan BLT Dana Desa sekarang juga. Gratis, mudah, dan transparan.</p>
    <button class="btn-cta-big" onclick="document.querySelector('.input-nik').focus();window.scrollTo({top:0,behavior:'smooth'})">
        🔍 Cek Status Saya
    </button>
</div>

<footer>
    <p>© {{ date('Y') }} <strong>Pemerintah Desa</strong> — Portal BLT Dana Desa &nbsp;|&nbsp; Transparansi untuk warga</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Scroll ke hasil
    const resultEl = document.querySelector('.result-wrapper');
    if (resultEl) setTimeout(() => resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' }), 300);

    // Animate on scroll
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.step-card, .laporan-card, .galeri-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity .5s ease, transform .5s ease';
        obs.observe(el);
    });

    // Input NIK: angka saja
    const nikEl = document.querySelector('.input-nik');
    if (nikEl) {
        nikEl.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });
    }
});
</script>
</body>
</html>