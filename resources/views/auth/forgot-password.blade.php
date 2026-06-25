<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — SiBantuDes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(145deg, #eff6ff 0%, #f8fafc 45%, #f0f9ff 100%);
            -webkit-font-smoothing: antialiased;
        }

        .shell {
            width: 100%;
            max-width: 960px;
            min-height: 560px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.18), 0 0 0 1px rgba(255,255,255,.06);
        }

        /* ── LEFT PANEL ── */
        .left {
            width: 50%;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        .left-img {
            position: absolute; inset: 0;
            background: url('/E31232406/public/login-gedung.jpg') center/cover no-repeat;
        }
        .left-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(170deg, rgba(15,23,42,.45) 0%, rgba(15,23,42,.25) 40%, rgba(15,23,42,.78) 100%);
        }
        .left-content {
            position: relative; z-index: 2; height: 100%;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 32px 36px;
        }
        .left-brand { display: flex; align-items: center; gap: 10px; }
        .left-brand-name { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: -.02em; }
        .left-brand-sub  { font-size: 11px; color: rgba(255,255,255,.75); font-weight: 500; }
        .left-caption { font-size: 22px; font-weight: 900; color: #fff; line-height: 1.25; letter-spacing: -.03em; margin-bottom: 16px; }
        .left-caption span { color: #93c5fd; }
        .left-features { display: flex; flex-direction: column; gap: 10px; }
        .feat { display: flex; align-items: center; gap: 9px; }
        .feat-dot {
            width: 20px; height: 20px; background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25); border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; backdrop-filter: blur(4px);
        }
        .feat-dot svg { width: 11px; height: 11px; color: #93c5fd; }
        .feat span { font-size: 12px; color: rgba(255,255,255,.88); font-weight: 500; }
        .loc-tag {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
            backdrop-filter: blur(6px); padding: 4px 10px; border-radius: 20px;
            font-size: 10.5px; color: rgba(255,255,255,.8); font-weight: 600; margin-bottom: 14px;
        }
        .loc-tag svg { width: 11px; height: 11px; }

        /* ── RIGHT PANEL ── */
        .right {
            flex: 1; background: #fff;
            display: flex; flex-direction: column; justify-content: center;
            padding: 36px 44px; overflow-y: auto;
        }

        /* ── BACK BUTTON ── */
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 700; color: #64748b; text-decoration: none;
            background: #f8fafc; border: 1.5px solid #e2e8f0; padding: 6px 12px;
            border-radius: 10px; transition: background .15s, color .15s, border-color .15s;
            margin-bottom: 20px; width: fit-content;
        }
        .btn-back:hover { background: #fff; color: #2563eb; border-color: #bfdbfe; }
        .btn-back svg { width: 13px; height: 13px; flex-shrink: 0; }

        /* ── LOGO + HEADER ── */
        .fp-logo { display: flex; justify-content: center; margin-bottom: 12px; }
        .fp-logo img { width: 46px; height: 46px; border-radius: 13px; object-fit: cover; box-shadow: 0 8px 20px rgba(37,99,235,.15); }
        .fp-eyebrow { text-align: center; font-size: 11px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
        .fp-title   { text-align: center; font-size: 22px; font-weight: 900; color: #0f172a; letter-spacing: -.03em; line-height: 1.1; margin-bottom: 6px; }
        .fp-sub     { font-size: 12.5px; line-height: 1.7; color: #64748b; text-align: center; margin-bottom: 18px; }

        /* ── STEP INDICATOR ── */
        .fp-steps { display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
        .fp-step  { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .fp-step-dot {
            width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800;
            border: 2px solid #e2e8f0; color: #94a3b8; background: #f8fafc; transition: all .2s;
        }
        .fp-step-dot.active { background: #2563eb; border-color: #2563eb; color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,.3); }
        .fp-step-dot.done   { background: #10b981; border-color: #10b981; color: #fff; }
        .fp-step-label { font-size: 10px; font-weight: 600; color: #94a3b8; white-space: nowrap; }
        .fp-step-label.active { color: #2563eb; }
        .fp-step-label.done   { color: #10b981; }
        .fp-step-line { width: 44px; height: 2px; background: #e2e8f0; margin: 0 4px; margin-bottom: 18px; border-radius: 99px; transition: background .2s; }
        .fp-step-line.done { background: #10b981; }

        /* ── ALERTS ── */
        .fp-alert {
            margin-bottom: 13px; padding: 10px 13px; border-radius: 11px;
            font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;
        }
        .fp-alert svg { width: 14px; height: 14px; flex-shrink: 0; }
        .fp-alert.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .fp-alert.error   { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

        /* ── FIELDS ── */
        .fp-field { margin-bottom: 13px; }
        .fp-label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 700; color: #334155; letter-spacing: .01em; }
        .fp-input-wrap { position: relative; }
        .fp-input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 28px; height: 28px; border-radius: 8px; background: #eff6ff;
            display: flex; align-items: center; justify-content: center; pointer-events: none;
        }
        .fp-input-icon svg { width: 13px; height: 13px; stroke: #2563eb; }
        .fp-input {
            width: 100%; height: 46px; padding: 0 14px 0 50px;
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            font-size: 16px; color: #0f172a; background: #f8fafc;
            outline: none; font-family: inherit;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        @media(min-width:640px){ .fp-input { font-size: 13.5px; } }
        .fp-input:focus { border-color: #93c5fd; background: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,.10); }
        .fp-input::placeholder { color: #cbd5e1; }
        .fp-input-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #94a3b8; background: none; border: none;
            border-radius: 7px; transition: color .15s, background .15s;
        }
        .fp-input-toggle:hover { color: #475569; background: #f1f5f9; }
        .fp-input-toggle svg { width: 14px; height: 14px; }
        .fp-hint { margin-top: 4px; font-size: 11px; color: #94a3b8; font-weight: 500; }
        .fp-hint.error { color: #dc2626; }
        .fp-strength { margin-top: 5px; display: flex; gap: 4px; }
        .fp-strength-seg { flex: 1; height: 3px; border-radius: 99px; background: #e2e8f0; transition: background .2s; }

        /* ── BUTTON ── */
        .fp-btn {
            width: 100%; height: 46px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; font-size: 13.5px; font-weight: 800; letter-spacing: -.01em;
            cursor: pointer; font-family: inherit;
            box-shadow: 0 6px 16px rgba(37,99,235,.28);
            transition: transform .12s, box-shadow .12s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .fp-btn:hover   { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(37,99,235,.34); }
        .fp-btn:active  { transform: scale(.98); }
        .fp-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; }

        .fp-footer { margin-top: 14px; text-align: center; font-size: 12px; color: #64748b; }
        .fp-footer a { color: #2563eb; text-decoration: none; font-weight: 700; }
        .fp-footer a:hover { text-decoration: underline; }

        .fp-section { display: none; }
        .fp-section.visible { display: block; }

        /* ── MOBILE ── */
        @media(max-width:768px){
            body { padding: 0; align-items: stretch; min-height: 100dvh; }
            .shell { flex-direction: column; border-radius: 0; min-height: 100dvh; box-shadow: none; max-width: 100%; }
            .left  { display: none; }
            .right { flex: 1; padding: 40px 24px 32px; justify-content: center; min-height: 100dvh; }
            .fp-title { font-size: 20px; }
        }
        @media(max-width:390px){
            .right { padding: 32px 18px 28px; }
        }
    </style>
</head>
<body>
<div class="shell">

    {{-- ── LEFT PANEL ── --}}
    <div class="left">
        <div class="left-img"></div>
        <div class="left-overlay"></div>
        <div class="left-content">
            <div class="left-brand">
                <img src="{{ asset('favicon.ico') }}" alt="Logo SiBantuDes"
                     style="width:38px;height:38px;border-radius:11px;box-shadow:0 3px 10px rgba(0,0,0,.3);object-fit:cover;">
                <div>
                    <div class="left-brand-name">SiBantuDes</div>
                    <div class="left-brand-sub">Sistem Bantuan Tunai Desa</div>
                </div>
            </div>
            <div>
                <div class="loc-tag">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Desa Ngerong
                </div>
                <div class="left-caption">Penyaluran Bantuan<br>yang <span>Transparan</span><br>&amp; Akuntabel</div>
                <div class="left-features">
                    <div class="feat"><div class="feat-dot"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div><span>Pendataan warga calon penerima bantuan</span></div>
                    <div class="feat"><div class="feat-dot"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div><span>Prediksi kelayakan berbasis Machine Learning</span></div>
                    <div class="feat"><div class="feat-dot"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div><span>Laporan &amp; monitoring penyaluran bantuan</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="right">

        {{-- <a href="{{ route('login') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Login
        </a> --}}

        <div class="fp-logo">
            <img src="{{ asset('favicon.ico') }}" alt="Logo SiBantuDes">
        </div>

        <div class="fp-eyebrow">Reset Password</div>
        <div class="fp-title" id="fp-title">Lupa Password?</div>
        <div class="fp-sub" id="fp-sub">Masukkan email yang terdaftar untuk memulai reset password.</div>

        <div class="fp-steps">
            <div class="fp-step">
                <div class="fp-step-dot active" id="dot-1">1</div>
                <div class="fp-step-label active" id="lbl-1">Verifikasi</div>
            </div>
            <div class="fp-step-line" id="line-1"></div>
            <div class="fp-step">
                <div class="fp-step-dot" id="dot-2">2</div>
                <div class="fp-step-label" id="lbl-2">Password Baru</div>
            </div>
            <div class="fp-step-line" id="line-2"></div>
            <div class="fp-step">
                <div class="fp-step-dot" id="dot-3">3</div>
                <div class="fp-step-label" id="lbl-3">Selesai</div>
            </div>
        </div>

        <div id="fp-alert-area"></div>

        @if($errors->any())
            <div class="fp-alert error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- STEP 1 --}}
        <div class="fp-section visible" id="step-1">
            <div class="fp-field">
                <label class="fp-label" for="s1-email">Alamat Email</label>
                <div class="fp-input-wrap">
                    <div class="fp-input-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <input id="s1-email" type="email" class="fp-input" placeholder="nama@email.com" autocomplete="email" autofocus>
                </div>
                <div class="fp-hint" id="s1-email-hint"></div>
            </div>
            <button class="fp-btn" id="btn-verify" onclick="verifyEmail()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi Email
            </button>
        </div>

        {{-- STEP 2 --}}
        <div class="fp-section" id="step-2">
            <div class="fp-field">
                <label class="fp-label">Password Baru</label>
                <div class="fp-input-wrap">
                    <div class="fp-input-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="s2-password" type="password" class="fp-input" placeholder="Minimal 8 karakter" oninput="checkStrength()">
                    <button type="button" class="fp-input-toggle" onclick="togglePw('s2-password', this)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="fp-strength"><div class="fp-strength-seg" id="seg1"></div><div class="fp-strength-seg" id="seg2"></div><div class="fp-strength-seg" id="seg3"></div><div class="fp-strength-seg" id="seg4"></div></div>
                <div class="fp-hint" id="s2-pw-hint"></div>
            </div>
            <div class="fp-field">
                <label class="fp-label">Konfirmasi Password Baru</label>
                <div class="fp-input-wrap">
                    <div class="fp-input-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <input id="s2-confirm" type="password" class="fp-input" placeholder="Ulangi password baru" oninput="checkMatch()">
                    <button type="button" class="fp-input-toggle" onclick="togglePw('s2-confirm', this)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="fp-hint" id="s2-confirm-hint"></div>
            </div>
            <button class="fp-btn" id="btn-reset" onclick="submitReset()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Password Baru
            </button>
        </div>

        {{-- STEP 3 --}}
        <div class="fp-section" id="step-3" style="text-align:center;padding:8px 0;">
            <div style="width:54px;height:54px;border-radius:50%;background:#f0fdf4;border:2px solid #bbf7d0;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <svg width="24" height="24" fill="none" stroke="#10b981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;font-weight:800;color:#0f172a;margin-bottom:5px;">Password Berhasil Diubah!</p>
            <p style="font-size:12.5px;color:#64748b;line-height:1.6;margin-bottom:16px;">Password akun Anda sudah diperbarui. Silakan login menggunakan password baru.</p>
            <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;height:46px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;font-size:13.5px;font-weight:800;text-decoration:none;box-shadow:0 6px 16px rgba(37,99,235,.28);">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                Kembali ke Login
            </a>
        </div>

        <div class="fp-footer" id="fp-footer-link">
            Sudah ingat password? <a href="{{ route('login') }}">Kembali ke login</a>
        </div>

    </div>
</div>

<script>
var verifiedEmail = '';

function togglePw(inputId, btn) {
    var inp = document.getElementById(inputId);
    var showing = inp.type === 'text';
    inp.type = showing ? 'password' : 'text';
    btn.querySelector('svg').innerHTML = showing
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
}

function checkStrength() {
    var v = document.getElementById('s2-password').value;
    var score = 0;
    if (v.length >= 8)           score++;
    if (/[A-Z]/.test(v))         score++;
    if (/[0-9]/.test(v))         score++;
    if (/[^A-Za-z0-9]/.test(v))  score++;
    var colors = ['#f43f5e','#f59e0b','#3b82f6','#10b981'];
    var labels = ['Sangat lemah','Cukup','Kuat','Sangat kuat'];
    for (var i = 1; i <= 4; i++) {
        document.getElementById('seg'+i).style.background = i <= score ? colors[score-1] : '#e2e8f0';
    }
    var hint = document.getElementById('s2-pw-hint');
    hint.textContent = v.length ? (labels[score-1] || '') : '';
    hint.className   = 'fp-hint';
    checkMatch();
}

function checkMatch() {
    var pw   = document.getElementById('s2-password').value;
    var cfm  = document.getElementById('s2-confirm').value;
    var hint = document.getElementById('s2-confirm-hint');
    if (!cfm) { hint.textContent = ''; return; }
    if (pw === cfm) { hint.textContent = '✓ Password cocok'; hint.className = 'fp-hint'; hint.style.color = '#10b981'; }
    else            { hint.textContent = 'Password tidak cocok'; hint.className = 'fp-hint error'; }
}

function showAlert(type, msg) {
    var icon = type === 'success'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>';
    document.getElementById('fp-alert-area').innerHTML =
        '<div class="fp-alert '+type+'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">'+icon+'</svg>'+msg+'</div>';
}
function clearAlert() { document.getElementById('fp-alert-area').innerHTML = ''; }

function goStep(n) {
    [1,2,3].forEach(function(i){
        document.getElementById('step-'+i).classList.toggle('visible', i === n);
        var dot = document.getElementById('dot-'+i);
        var lbl = document.getElementById('lbl-'+i);
        dot.className = 'fp-step-dot ' + (i < n ? 'done' : i === n ? 'active' : '');
        lbl.className = 'fp-step-label ' + (i < n ? 'done' : i === n ? 'active' : '');
        dot.innerHTML = i < n ? '&#10003;' : String(i);
    });
    document.getElementById('line-1').className = 'fp-step-line' + (n > 1 ? ' done' : '');
    document.getElementById('line-2').className = 'fp-step-line' + (n > 2 ? ' done' : '');
    var titles = ['','Lupa Password?','Password Baru','Selesai!'];
    var subs   = ['','Masukkan email yang terdaftar untuk memulai reset password.',
                     'Buat password baru untuk akun <strong>'+verifiedEmail+'</strong>.',''];
    document.getElementById('fp-title').textContent = titles[n];
    document.getElementById('fp-sub').innerHTML     = subs[n];
    document.getElementById('fp-footer-link').style.display = n === 3 ? 'none' : '';
}

function verifyEmail() {
    var email = document.getElementById('s1-email').value.trim();
    var hint  = document.getElementById('s1-email-hint');
    if (!email) { hint.textContent = 'Email wajib diisi.'; hint.className = 'fp-hint error'; return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { hint.textContent = 'Format email tidak valid.'; hint.className = 'fp-hint error'; return; }
    hint.textContent = '';
    var btn = document.getElementById('btn-verify');
    var spinSvg = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    var checkSvg = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    btn.disabled = true;
    btn.innerHTML = spinSvg + ' Memverifikasi...';
    fetch('{{ route("password.manual.check") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ email: email })
    })
    .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, data: d }; }); })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = checkSvg + ' Verifikasi Email';
        if (!res.ok) { showAlert('error', res.data.message || 'Terjadi kesalahan server.'); return; }
        if (res.data.found) { verifiedEmail = email; clearAlert(); goStep(2); }
        else showAlert('error', 'Email <strong>'+email+'</strong> tidak terdaftar dalam sistem.');
    })
    .catch(function(){ btn.disabled = false; btn.innerHTML = checkSvg + ' Verifikasi Email'; showAlert('error', 'Tidak dapat terhubung ke server.'); });
}

function submitReset() {
    var pw  = document.getElementById('s2-password').value;
    var cfm = document.getElementById('s2-confirm').value;
    if (pw.length < 8) { document.getElementById('s2-pw-hint').textContent = 'Password minimal 8 karakter.'; document.getElementById('s2-pw-hint').className = 'fp-hint error'; return; }
    if (pw !== cfm)    { document.getElementById('s2-confirm-hint').textContent = 'Password tidak cocok.'; document.getElementById('s2-confirm-hint').className = 'fp-hint error'; return; }
    var btn = document.getElementById('btn-reset');
    var spinSvg  = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    var checkSvg = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
    btn.disabled = true;
    btn.innerHTML = spinSvg + ' Menyimpan...';
    fetch('{{ route("password.manual.update") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ email: verifiedEmail, password: pw, password_confirmation: cfm })
    })
    .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, data: d }; }); })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = checkSvg + ' Simpan Password Baru';
        if (res.data.success) { clearAlert(); goStep(3); }
        else showAlert('error', res.data.message || 'Gagal menyimpan password.');
    })
    .catch(function(){ btn.disabled = false; btn.innerHTML = checkSvg + ' Simpan Password Baru'; showAlert('error', 'Tidak dapat terhubung ke server.'); });
}

document.addEventListener('keydown', function(e){
    if (e.key !== 'Enter') return;
    if (document.getElementById('step-1').classList.contains('visible')) verifyEmail();
    else if (document.getElementById('step-2').classList.contains('visible')) submitReset();
});

var st = document.createElement('style');
st.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(st);
</script>
</body>
</html>