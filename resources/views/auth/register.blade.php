<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — SiBantuDes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
   <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        min-height: 100vh;
        font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
        background: linear-gradient(145deg, #eff6ff 0%, #f8fafc 45%, #f0f9ff 100%);
        display: flex; align-items: center; justify-content: center;
        padding: 12px 16px;  /* ← dari 24px */
    }
    .card { width:100%; max-width:460px; background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 2px 4px rgba(0,0,0,0.04),0 8px 24px rgba(37,99,235,0.08),0 24px 48px rgba(37,99,235,0.06); }
    .hero { position:relative; padding:20px 28px 18px; background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 55%,#3b82f6 100%); overflow:hidden; }  /* ← dari 30px 32px 28px */
    .hero-blob-1 { position:absolute; top:-32px; right:-32px; width:130px; height:130px; background:rgba(255,255,255,.09); border-radius:50%; }
    .hero-blob-2 { position:absolute; bottom:-40px; left:-16px; width:100px; height:100px; background:rgba(255,255,255,.07); border-radius:50%; }
    .hero-blob-3 { position:absolute; top:10px; right:90px; width:40px; height:40px; background:rgba(255,255,255,.10); border-radius:50%; }
    .hero-inner  { position:relative; z-index:1; display:flex; align-items:center; gap:14px; }
    .hero-logo   { width:48px; height:48px; flex-shrink:0; background:rgba(255,255,255,.16); border:2px solid rgba(255,255,255,.22); border-radius:14px; display:flex; align-items:center; justify-content:center; }  /* ← dari 56px */
    .hero-logo svg { width:24px; height:24px; stroke:#fff; }
    .hero-app    { font-size:10px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:rgba(191,219,254,.85); margin-bottom:2px; }
    .hero-title  { font-size:19px; font-weight:800; color:#fff; line-height:1.2; }  /* ← dari 21px */
    .hero-sub    { font-size:12px; color:rgba(191,219,254,.8); margin-top:1px; }
    .body { padding:18px 28px 14px; }  /* ← dari 28px 32px 24px */
    .error-box { display:flex; gap:10px; align-items:flex-start; background:#fef2f2; border:1.5px solid #fecaca; border-radius:12px; padding:10px 12px; margin-bottom:14px; }
    .error-box svg { width:15px; height:15px; stroke:#ef4444; flex-shrink:0; margin-top:1px; }
    .error-box ul { list-style:none; }
    .error-box li { font-size:12px; color:#dc2626; line-height:1.5; }
    .field       { margin-bottom:11px; }  /* ← dari 16px */
    .field-label { display:block; margin-bottom:5px; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; }
    .field-label .req { color:#ef4444; margin-left:2px; }
    .field-hint  { font-size:11px; color:#9ca3af; margin-top:4px; padding-left:2px; }
    .iw { position:relative; display:flex; align-items:center; }
    .iw-icon { position:absolute; left:10px; z-index:1; pointer-events:none; width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; }
    .iw-icon svg { width:14px; height:14px; fill:none; }
    .iw-icon.blue  { background:#eff6ff; } .iw-icon.blue  svg { stroke:#3b82f6; }
    .iw-icon.green { background:#f0fdf4; } .iw-icon.green svg { stroke:#22c55e; }
    .iw-icon.amber { background:#fffbeb; } .iw-icon.amber svg { stroke:#f59e0b; }
    .iw input, .iw select { width:100%; padding:10px 14px 10px 50px; font-size:13.5px; color:#111827; background:#f9fafb; border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:border-color .15s,background .15s,box-shadow .15s; -webkit-appearance:none; appearance:none; }  /* ← padding dari 13px */
    .iw input::placeholder { color:#9ca3af; font-size:13px; }
    .iw input:focus, .iw select:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
    .iw input.err { border-color:#fca5a5; background:#fef2f2; }
    .iw-arrow { position:absolute; right:12px; pointer-events:none; }
    .iw-arrow svg { width:14px; height:14px; stroke:#9ca3af; fill:none; }
    .iw-toggle { position:absolute; right:9px; width:28px; height:28px; border-radius:7px; background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#9ca3af; transition:color .15s,background .15s; }
    .iw-toggle:hover { color:#6b7280; background:#f3f4f6; }
    .iw-toggle svg { width:14px; height:14px; stroke:currentColor; fill:none; }
    .divider { display:flex; align-items:center; gap:10px; margin:4px 0 12px; }  /* ← dari 6px 0 18px */
    .divider::before, .divider::after { content:''; flex:1; height:1px; background:#f1f5f9; }
    .divider span { font-size:10.5px; font-weight:600; color:#cbd5e1; white-space:nowrap; }
    .btn-submit { width:100%; margin-top:8px; padding:12px; border:none; border-radius:12px; font-size:14px; font-weight:700; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%); box-shadow:0 4px 16px rgba(37,99,235,.32); transition:box-shadow .2s,transform .1s; }  /* ← padding dari 14px */
    .btn-submit:hover  { box-shadow:0 6px 24px rgba(37,99,235,.44); }
    .btn-submit:active { transform:scale(.98); }
    .btn-submit svg    { width:16px; height:16px; stroke:#fff; fill:none; flex-shrink:0; }
    .login-link { margin-top:14px; padding-top:12px; border-top:1px solid #f1f5f9; text-align:center; font-size:13px; color:#9ca3af; }  /* ← dari 20px/18px */
    .login-link a { color:#2563eb; font-weight:600; text-decoration:none; margin-left:2px; }
    .login-link a:hover { text-decoration:underline; }
    .footer { padding:9px 28px; background:#f9fafb; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:center; gap:6px; }  /* ← dari 11px */
    .footer svg  { width:12px; height:12px; fill:#d1d5db; }
    .footer span { font-size:11px; color:#d1d5db; font-weight:500; }
</style>
</head>
<body>
<div class="card">
    <div class="hero">
        <div class="hero-blob-1"></div><div class="hero-blob-2"></div><div class="hero-blob-3"></div>
        <div class="hero-inner">
            <div class="hero-logo">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
                <p class="hero-app">SiBantuDes</p>
                <h1 class="hero-title">Daftar Akun RT</h1>
                <p class="hero-sub">Lengkapi data untuk mendaftar ke sistem</p>
            </div>
        </div>
    </div>
    <div class="body">
        @if($errors->any())
        <div class="error-box">
            <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="field">
                <label class="field-label">Nama Lengkap <span class="req">*</span></label>
                <div class="iw">
                    <div class="iw-icon blue"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" class="{{ $errors->has('name') ? 'err' : '' }}">
                </div>
            </div>
            <div class="field">
                <label class="field-label">Email <span class="req">*</span></label>
                <div class="iw">
                    <div class="iw-icon blue"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="{{ $errors->has('email') ? 'err' : '' }}">
                </div>
            </div>
            <div class="field">
                <label class="field-label">Pilih RT <span class="req">*</span></label>
                <div class="iw">
                    <div class="iw-icon green"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <select name="rt_id" required style="padding-right:40px;" class="{{ $errors->has('rt_id') ? 'err' : '' }}">
                        <option value="">— Pilih RT —</option>
                        @forelse(($rts ?? []) as $rt)
                            <option value="{{ $rt->id }}" {{ (string)old('rt_id') === (string)$rt->id ? 'selected' : '' }}>RT {{ str_pad($rt->nomor_rt, 3, '0', STR_PAD_LEFT) }} — {{ $rt->dusun->nama_dusun ?? '-' }}</option>
                        @empty
                            <option value="" disabled>Data RT belum tersedia</option>
                        @endforelse
                    </select>
                    <div class="iw-arrow"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                </div>
                <p class="field-hint">Dusun mengikuti RT yang dipilih secara otomatis.</p>
            </div>
            <div class="divider"><span>Keamanan Akun</span></div>
            <div class="field">
                <label class="field-label">Password <span class="req">*</span></label>
                <div class="iw">
                    <div class="iw-icon amber"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
                    <input type="password" name="password" id="pwd" required placeholder="Minimal 6 karakter" style="padding-right:46px;" class="{{ $errors->has('password') ? 'err' : '' }}">
                    <button type="button" class="iw-toggle" onclick="togglePwd('pwd',this)"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></button>
                </div>
            </div>
            <div class="field">
                <label class="field-label">Konfirmasi Password <span class="req">*</span></label>
                <div class="iw">
                    <div class="iw-icon amber"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                    <input type="password" name="password_confirmation" id="pwd2" required placeholder="Ulangi password" style="padding-right:46px;">
                    <button type="button" class="iw-toggle" onclick="togglePwd('pwd2',this)"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></button>
                </div>
            </div>
            <button type="submit" class="btn-submit">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Daftar Sekarang
            </button>
        </form>
        <div class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini →</a></div>
    </div>
    <div class="footer">
        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
        <span>SiBantuDes &middot; Desa Ngerong</span>
    </div>
</div>
<script>
function togglePwd(id,btn){const i=document.getElementById(id);const s=i.type==='password';i.type=s?'text':'password';btn.querySelector('svg').innerHTML=s?'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';}
</script>
</body>
</html>