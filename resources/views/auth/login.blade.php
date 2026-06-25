<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SiBantuDes</title>
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
            position: absolute;
            inset: 0;
            background: url('/E31232406/public/login-gedung.jpg') center/cover no-repeat;
        }

        .left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                170deg,
                rgba(15,23,42,.45) 0%,
                rgba(15,23,42,.25) 40%,
                rgba(15,23,42,.78) 100%
            );
        }

        .left-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 32px 36px;
        }

        .left-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .left-brand-name {
            font-size: 16px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.02em;
        }

        .left-brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,.75);
            font-weight: 500;
        }

        .left-caption {
            font-size: 22px;
            font-weight: 900;
            color: #fff;
            line-height: 1.25;
            letter-spacing: -.03em;
            margin-bottom: 16px;
        }

        .left-caption span { color: #93c5fd; }

        .left-features {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feat {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .feat-dot {
            width: 20px;
            height: 20px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }

        .feat-dot svg { width: 11px; height: 11px; color: #93c5fd; }
        .feat span { font-size: 12px; color: rgba(255,255,255,.88); font-weight: 500; }

        .loc-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            backdrop-filter: blur(6px);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10.5px;
            color: rgba(255,255,255,.8);
            font-weight: 600;
            margin-bottom: 14px;
        }

        .loc-tag svg { width: 11px; height: 11px; }

        /* ── RIGHT PANEL ── */
        .right {
            flex: 1;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 44px;
            position: relative;    /* untuk tombol back absolute di mobile */
        }

        /* ── BACK BUTTON ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 10px;
            transition: background .15s, color .15s, border-color .15s;
            margin-bottom: 22px;
            width: fit-content;
        }

        .btn-back:hover {
            background: #fff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .btn-back svg { width: 13px; height: 13px; flex-shrink: 0; }

        /* ── FORM ── */
        .form-head { margin-bottom: 24px; }

        .form-eyebrow {
            font-size: 10.5px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 6px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -.04em;
            line-height: 1.1;
        }

        .form-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 5px;
            font-weight: 500;
        }

        .err-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff1f2;
            border: 1.5px solid #fecdd3;
            color: #be123c;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .err-box svg { width: 15px; height: 15px; flex-shrink: 0; }

        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: .01em;
        }

        .iw { position: relative; }

        .iw-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .iw-icon svg { width: 14px; height: 14px; stroke: #2563eb; }

        .iw input {
            width: 100%;
            padding: 12px 14px 12px 50px;
            border: 1.5px solid #e2e8f0;
            border-radius: 13px;
            font-size: 16px;          /* cegah auto-zoom iOS */
            font-family: inherit;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        @media (min-width: 640px) {
            .iw input { font-size: 13.5px; }
        }

        .iw input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
            background: #fff;
        }

        .iw input::placeholder { color: #cbd5e1; }

        .iw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #94a3b8;
            background: none;
            border: none;
            border-radius: 7px;
            transition: color .15s, background .15s;
        }

        .iw-toggle:hover { color: #475569; background: #f1f5f9; }
        .iw-toggle svg { width: 15px; height: 15px; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type=checkbox] {
            width: 15px;
            height: 15px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 12.5px;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 16px rgba(37,99,235,.35);
            transition: transform .12s, box-shadow .12s;
            letter-spacing: -.01em;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(37,99,235,.4);
        }

        .btn-submit:active { transform: scale(.98); }

        .reg-row {
            text-align: center;
            margin-top: 18px;
            font-size: 12.5px;
            color: #94a3b8;
            font-weight: 500;
        }

        .reg-row a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .reg-row a:hover { text-decoration: underline; }

        .form-footer {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 11px;
            color: #cbd5e1;
            font-weight: 500;
        }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            body {
                padding: 0;
                align-items: stretch;
                min-height: 100dvh;
            }

            .shell {
                flex-direction: column;
                border-radius: 0;
                min-height: 100dvh;
                box-shadow: none;
                max-width: 100%;
            }

            .left { display: none; }

            .right {
                flex: 1;
                padding: 48px 28px 36px;
                justify-content: center;
                min-height: 100dvh;
            }

            .form-title { font-size: 22px; }

            .btn-submit {
                padding: 14px;
                font-size: 15px;
            }
        }

        @media (max-width: 390px) {
            .right { padding: 36px 20px 28px; }
            .form-title { font-size: 20px; }
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
                <img
                    src="{{ asset('favicon.ico') }}"
                    alt="Logo SiBantuDes"
                    style="width:38px;height:38px;border-radius:11px;box-shadow:0 3px 10px rgba(0,0,0,.3);object-fit:cover;"
                >
                <div>
                    <div class="left-brand-name">SiBantuDes</div>
                    <div class="left-brand-sub">Sistem Bantuan Tunai Desa</div>
                </div>
            </div>

            <div class="left-bottom">
                <div class="loc-tag">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Desa Ngerong
                </div>

                <div class="left-caption">
                    Penyaluran Bantuan<br>yang <span>Transparan</span><br>&amp; Akuntabel
                </div>

                <div class="left-features">
                    <div class="feat">
                        <div class="feat-dot">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Pendataan warga calon penerima bantuan</span>
                    </div>
                    <div class="feat">
                        <div class="feat-dot">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Prediksi kelayakan berbasis Machine Learning</span>
                    </div>
                    <div class="feat">
                        <div class="feat-dot">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Laporan &amp; monitoring penyaluran bantuan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="right">

        {{-- Tombol kembali ke landing page --}}
        <a href="{{ url('/') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
        </a>

        <div class="form-head">
            <div class="form-eyebrow">BLT-DD &bull; Desa Ngerong</div>
            <div class="form-title">Selamat Datang</div>
            <div class="form-sub">Masuk dengan akun yang telah terdaftar</div>
        </div>

        @if($errors->any())
            <div class="err-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="field">
                <label for="email">Alamat Email</label>
                <div class="iw">
                    <div class="iw-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="iw">
                    <div class="iw-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••">
                    <button type="button" class="iw-toggle" onclick="togglePw()" id="pw-toggle">
                        <svg id="pw-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn-submit" id="btn-login" onclick="handleLogin(this)">Masuk ke Sistem</button>
        </form>

        <div class="reg-row">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sebagai RT</a>
        </div>

        <div class="reg-row" style="margin-top:10px;">
            <a href="{{ route('password.request') }}">Lupa password?</a>
        </div>

        <div class="form-footer">
            &copy; 2025 SiBantuDes &mdash; Sistem BLT Dana Desa
        </div>
    </div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('pw-eye');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        inp.type = 'password';
        ico.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}

function handleLogin(btn) {
    /* validasi sederhana sebelum submit */
    var email = document.getElementById('email').value.trim();
    var pass  = document.getElementById('password').value;
    if (!email || !pass) return; /* biarkan browser handle required */

    /* ubah tombol jadi loading state */
    btn.disabled = true;
    btn.innerHTML =
        '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" ' +
        'style="animation:spin .8s linear infinite;flex-shrink:0;">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" ' +
        'd="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581' +
        'm0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>' +
        ' Sedang masuk...';
    btn.style.display = 'inline-flex';
    btn.style.alignItems = 'center';
    btn.style.justifyContent = 'center';
    btn.style.gap = '8px';

    /* submit form */
    btn.closest('form').submit();
}

/* re-enable tombol jika user balik (bfcache / back button) */
window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        var btn = document.getElementById('btn-login');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'Masuk ke Sistem';
            btn.style.display = '';
            btn.style.alignItems = '';
            btn.style.justifyContent = '';
            btn.style.gap = '';
        }
    }
});
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

</body>
</html>