<x-guest-layout>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(145deg, #eff6ff 0%, #f8fafc 45%, #f0f9ff 100%);
        }

        .fp-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .fp-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 24px;
            padding: 32px 30px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .fp-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .fp-logo img {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            object-fit: cover;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.15);
        }

        .fp-eyebrow {
            text-align: center;
            font-size: 11px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 8px;
        }

        .fp-title {
            text-align: center;
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -.03em;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .fp-sub {
            font-size: 13px;
            line-height: 1.7;
            color: #64748b;
            text-align: center;
            margin-bottom: 22px;
        }

        /* Step indicator */
        .fp-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 24px;
        }
        .fp-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .fp-step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            border: 2px solid #e2e8f0;
            color: #94a3b8;
            background: #f8fafc;
            transition: all .2s;
        }
        .fp-step-dot.active {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,.3);
        }
        .fp-step-dot.done {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }
        .fp-step-label {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            white-space: nowrap;
        }
        .fp-step-label.active { color: #2563eb; }
        .fp-step-label.done   { color: #10b981; }
        .fp-step-line {
            width: 48px;
            height: 2px;
            background: #e2e8f0;
            margin: 0 4px;
            margin-bottom: 18px;
            border-radius: 99px;
            transition: background .2s;
        }
        .fp-step-line.done { background: #10b981; }

        /* Alert boxes */
        .fp-alert {
            margin-bottom: 16px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 12.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .fp-alert svg { width: 15px; height: 15px; flex-shrink: 0; }
        .fp-alert.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .fp-alert.error   { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

        /* Fields */
        .fp-field { margin-bottom: 16px; }

        .fp-label {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            letter-spacing: .01em;
        }

        .fp-input-wrap { position: relative; }

        .fp-input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .fp-input-icon svg { width: 14px; height: 14px; stroke: #2563eb; }

        .fp-input {
            width: 100%;
            height: 50px;
            padding: 0 14px 0 52px;
            border: 1.5px solid #e2e8f0;
            border-radius: 13px;
            font-size: 16px; /* cegah auto-zoom iOS */
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            font-family: inherit;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        @media(min-width:640px){ .fp-input { font-size: 14px; } }

        .fp-input:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.10);
        }
        .fp-input::placeholder { color: #cbd5e1; }
        .fp-input.is-error { border-color: #fca5a5; background: #fff1f2; }

        .fp-input-toggle {
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
        .fp-input-toggle:hover { color: #475569; background: #f1f5f9; }
        .fp-input-toggle svg { width: 15px; height: 15px; }

        .fp-hint {
            margin-top: 5px;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }
        .fp-hint.error { color: #dc2626; }

        /* Strength bar */
        .fp-strength {
            margin-top: 6px;
            display: flex;
            gap: 4px;
        }
        .fp-strength-seg {
            flex: 1;
            height: 3px;
            border-radius: 99px;
            background: #e2e8f0;
            transition: background .2s;
        }

        /* Submit button */
        .fp-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -.01em;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 8px 20px rgba(37,99,235,.28);
            transition: transform .12s, box-shadow .12s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .fp-btn:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(37,99,235,.34); }
        .fp-btn:active { transform: scale(.98); }
        .fp-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; }

        .fp-footer {
            margin-top: 16px;
            text-align: center;
            font-size: 12.5px;
            color: #64748b;
        }
        .fp-footer a { color: #2563eb; text-decoration: none; font-weight: 700; }
        .fp-footer a:hover { text-decoration: underline; }

        /* Section toggle */
        .fp-section { display: none; }
        .fp-section.visible { display: block; }

        @media(max-width:480px){
            .fp-card { padding: 24px 18px; border-radius: 20px; }
            .fp-title { font-size: 22px; }
        }
    </style>

    <div class="fp-wrap">
        <div class="fp-card">

            <div class="fp-logo">
                <img src="{{ asset('favicon.ico') }}" alt="Logo SiBantuDes">
            </div>

            <div class="fp-eyebrow">Reset Password</div>
            <div class="fp-title" id="fp-title">Lupa Password?</div>
            <div class="fp-sub" id="fp-sub">Masukkan email yang terdaftar untuk memulai reset password.</div>

            {{-- Step indicator --}}
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

            {{-- Alert area --}}
            <div id="fp-alert-area"></div>

            {{-- SERVER-SIDE ERRORS --}}
            @if($errors->any())
                <div class="fp-alert error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- ═══ STEP 1: Verifikasi Email ═══ --}}
            <div class="fp-section visible" id="step-1">
                <div class="fp-field">
                    <label class="fp-label" for="s1-email">Alamat Email</label>
                    <div class="fp-input-wrap">
                        <div class="fp-input-icon">
                            <svg fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="s1-email" type="email" class="fp-input"
                               placeholder="nama@email.com" autocomplete="email" autofocus>
                    </div>
                    <div class="fp-hint" id="s1-email-hint"></div>
                </div>

                <button class="fp-btn" id="btn-verify" onclick="verifyEmail()">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Verifikasi Email
                </button>
            </div>

            {{-- ═══ STEP 2: Input Password Baru ═══ --}}
            <div class="fp-section" id="step-2">
                <div class="fp-field">
                    <label class="fp-label">Password Baru</label>
                    <div class="fp-input-wrap">
                        <div class="fp-input-icon">
                            <svg fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="s2-password" type="password" class="fp-input"
                               placeholder="Minimal 8 karakter" oninput="checkStrength()">
                        <button type="button" class="fp-input-toggle" onclick="togglePw('s2-password', this)">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="fp-strength" id="strength-bar">
                        <div class="fp-strength-seg" id="seg1"></div>
                        <div class="fp-strength-seg" id="seg2"></div>
                        <div class="fp-strength-seg" id="seg3"></div>
                        <div class="fp-strength-seg" id="seg4"></div>
                    </div>
                    <div class="fp-hint" id="s2-pw-hint"></div>
                </div>

                <div class="fp-field">
                    <label class="fp-label">Konfirmasi Password Baru</label>
                    <div class="fp-input-wrap">
                        <div class="fp-input-icon">
                            <svg fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input id="s2-confirm" type="password" class="fp-input"
                               placeholder="Ulangi password baru" oninput="checkMatch()">
                        <button type="button" class="fp-input-toggle" onclick="togglePw('s2-confirm', this)">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="fp-hint" id="s2-confirm-hint"></div>
                </div>

                <button class="fp-btn" id="btn-reset" onclick="submitReset()">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Password Baru
                </button>
            </div>

            {{-- ═══ STEP 3: Selesai ═══ --}}
            <div class="fp-section" id="step-3" style="text-align:center;padding:10px 0;">
                <div style="width:60px;height:60px;border-radius:50%;background:#f0fdf4;border:2px solid #bbf7d0;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg width="28" height="28" fill="none" stroke="#10b981" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p style="font-size:15px;font-weight:800;color:#0f172a;margin-bottom:6px;">Password Berhasil Diubah!</p>
                <p style="font-size:13px;color:#64748b;line-height:1.6;margin-bottom:20px;">Password akun Anda sudah diperbarui. Silakan login menggunakan password baru.</p>
                <a href="{{ route('login') }}"
                   style="display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;height:50px;border-radius:13px;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;font-size:14px;font-weight:800;text-decoration:none;box-shadow:0 8px 20px rgba(37,99,235,.28);">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>

            <div class="fp-footer" id="fp-footer-link">
                Sudah ingat password?
                <a href="{{ route('login') }}">Kembali ke login</a>
            </div>

        </div>
    </div>

    <script>
    var verifiedEmail = '';

    /* ── Toggle password visibility ── */
    function togglePw(inputId, btn) {
        var inp = document.getElementById(inputId);
        var showing = inp.type === 'text';
        inp.type = showing ? 'password' : 'text';
        btn.querySelector('svg').innerHTML = showing
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    }

    /* ── Password strength ── */
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
            var seg = document.getElementById('seg'+i);
            seg.style.background = i <= score ? colors[score-1] : '#e2e8f0';
        }
        var hint = document.getElementById('s2-pw-hint');
        hint.textContent = v.length ? labels[score-1] || '' : '';
        hint.className   = 'fp-hint';
        checkMatch();
    }

    function checkMatch() {
        var pw  = document.getElementById('s2-password').value;
        var cfm = document.getElementById('s2-confirm').value;
        var hint = document.getElementById('s2-confirm-hint');
        if (!cfm) { hint.textContent = ''; return; }
        if (pw === cfm) {
            hint.textContent = '✓ Password cocok';
            hint.className   = 'fp-hint';
            hint.style.color = '#10b981';
        } else {
            hint.textContent = 'Password tidak cocok';
            hint.className   = 'fp-hint error';
        }
    }

    /* ── Alert helper ── */
    function showAlert(type, msg) {
        var icon = type === 'success'
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>';
        document.getElementById('fp-alert-area').innerHTML =
            '<div class="fp-alert '+type+'">' +
            '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">'+icon+'</svg>' +
            msg + '</div>';
    }
    function clearAlert() {
        document.getElementById('fp-alert-area').innerHTML = '';
    }

    /* ── Step transition ── */
    function goStep(n) {
        [1,2,3].forEach(function(i){
            document.getElementById('step-'+i).classList.toggle('visible', i === n);
        });

        /* Update step indicators */
        [1,2,3].forEach(function(i){
            var dot = document.getElementById('dot-'+i);
            var lbl = document.getElementById('lbl-'+i);
            dot.className = 'fp-step-dot ' + (i < n ? 'done' : i === n ? 'active' : '');
            lbl.className = 'fp-step-label ' + (i < n ? 'done' : i === n ? 'active' : '');
            if (i < n) dot.innerHTML = '✓';
            else dot.innerHTML = i;
        });
        if (document.getElementById('line-1'))
            document.getElementById('line-1').className = 'fp-step-line' + (n > 1 ? ' done' : '');
        if (document.getElementById('line-2'))
            document.getElementById('line-2').className = 'fp-step-line' + (n > 2 ? ' done' : '');

        var titles = ['','Lupa Password?','Password Baru','Selesai!'];
        var subs   = ['','Masukkan email yang terdaftar untuk memulai reset password.',
                         'Buat password baru untuk akun <strong>'+verifiedEmail+'</strong>.',
                         ''];
        document.getElementById('fp-title').textContent = titles[n];
        document.getElementById('fp-sub').innerHTML = subs[n];
        document.getElementById('fp-footer-link').style.display = n === 3 ? 'none' : '';
    }

    /* ── STEP 1: Verifikasi Email ── */
    function verifyEmail() {
        var email = document.getElementById('s1-email').value.trim();
        var hint  = document.getElementById('s1-email-hint');

        if (!email) {
            hint.textContent = 'Email wajib diisi.';
            hint.className   = 'fp-hint error';
            return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            hint.textContent = 'Format email tidak valid.';
            hint.className   = 'fp-hint error';
            return;
        }

        hint.textContent = '';
        var btn = document.getElementById('btn-verify');
        btn.disabled = true;
        btn.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Memverifikasi...';

        var verifyBtnLabel = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Verifikasi Email';

        fetch('{{ route("password.manual.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(function(r){
            return r.json().then(function(d){ return { ok: r.ok, data: d }; });
        })
        .then(function(res) {
            btn.disabled = false;
            btn.innerHTML = verifyBtnLabel;
            if (!res.ok) {
                showAlert('error', res.data.message || 'Terjadi kesalahan server. Coba lagi.');
                return;
            }
            if (res.data.found) {
                verifiedEmail = email;
                clearAlert();
                goStep(2);
            } else {
                showAlert('error', 'Email <strong>'+email+'</strong> tidak terdaftar dalam sistem.');
            }
        })
        .catch(function(){
            btn.disabled = false;
            btn.innerHTML = verifyBtnLabel;
            showAlert('error', 'Tidak dapat terhubung ke server. Periksa koneksi dan coba lagi.');
        });
    }

    /* ── STEP 2: Submit Reset ── */
    function submitReset() {
        var pw  = document.getElementById('s2-password').value;
        var cfm = document.getElementById('s2-confirm').value;

        if (pw.length < 8) {
            document.getElementById('s2-pw-hint').textContent = 'Password minimal 8 karakter.';
            document.getElementById('s2-pw-hint').className   = 'fp-hint error';
            return;
        }
        if (pw !== cfm) {
            document.getElementById('s2-confirm-hint').textContent = 'Password tidak cocok.';
            document.getElementById('s2-confirm-hint').className   = 'fp-hint error';
            return;
        }

        var btn = document.getElementById('btn-reset');
        btn.disabled = true;
        btn.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...';

        var resetBtnLabel = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Password Baru';

        fetch('{{ route("password.manual.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: verifiedEmail, password: pw, password_confirmation: cfm })
        })
        .then(function(r){
            return r.json().then(function(d){ return { ok: r.ok, data: d }; });
        })
        .then(function(res) {
            btn.disabled = false;
            btn.innerHTML = resetBtnLabel;
            if (res.data.success) {
                clearAlert();
                goStep(3);
            } else {
                showAlert('error', res.data.message || 'Gagal menyimpan password. Coba lagi.');
            }
        })
        .catch(function(){
            btn.disabled = false;
            btn.innerHTML = resetBtnLabel;
            showAlert('error', 'Tidak dapat terhubung ke server. Periksa koneksi dan coba lagi.');
        });
    }

    /* Enter key support */
    document.addEventListener('keydown', function(e){
        if (e.key !== 'Enter') return;
        var s1 = document.getElementById('step-1');
        var s2 = document.getElementById('step-2');
        if (s1.classList.contains('visible')) verifyEmail();
        else if (s2.classList.contains('visible')) submitReset();
    });

    /* Spinner keyframe */
    var st = document.createElement('style');
    st.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(st);
    </script>
</x-guest-layout>