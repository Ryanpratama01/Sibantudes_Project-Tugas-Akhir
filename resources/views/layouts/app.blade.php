<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Lambang_Kabupaten_Pasuruan.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Figtree',sans-serif;background:#f1f5f9;min-height:100dvh;-webkit-font-smoothing:antialiased;}

        /* ══ NAVBAR ══ */
        #nb-wrap{
            position:sticky;top:0;z-index:50;
            padding:10px 16px 0;
            transition:padding .25s;
        }
        #nb-wrap.scrolled{ padding-top:0; }

        #nb-shell{
            max-width:80rem;margin:0 auto;
            background:rgba(255,255,255,.96);
            backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
            border:1.5px solid #e2e8f0;
            border-radius:16px;
            box-shadow:0 4px 24px rgba(15,23,42,.07);
            padding:10px 18px;
            transition:border-radius .25s,box-shadow .25s;
            will-change:transform;
            transform:translateZ(0);
        }
        #nb-wrap.scrolled #nb-shell{
            border-radius:0 0 16px 16px;
            border-top:none;
            box-shadow:0 8px 32px rgba(15,23,42,.10);
        }

        .nb-row{ display:flex;align-items:center;gap:10px; }

        /* ── LINKS ── */
        .nb-links{ display:flex;align-items:center;gap:5px;flex:1; }

        .nb-links a{
            display:inline-flex;align-items:center;
            padding:7px 13px;border-radius:10px;
            font-size:12.5px;font-weight:600;
            color:#64748b;background:#f8fafc;
            border:1.5px solid transparent;
            text-decoration:none;white-space:nowrap;
            transition:background .15s,color .15s,border-color .15s,box-shadow .15s,
                        max-width .2s ease, opacity .2s ease, padding .2s ease, margin .2s ease;
            max-width:200px;
            overflow:hidden;
            opacity:1;
            will-change:max-width, opacity;
        }
        .nb-links a:hover{ background:#fff;border-color:#e2e8f0;color:#1e293b; }
        .nb-links a.nb-active{
            background:#2563eb;color:#fff;
            border-color:#2563eb;
            box-shadow:0 3px 10px rgba(37,99,235,.25);
        }

        .nb-links.hide-inactive a:not(.nb-active){
            max-width:0;opacity:0;
            padding-left:0;padding-right:0;
            margin:0;border-width:0;
            pointer-events:none;
        }

        /* ── USER ── */
        .nb-user{ display:flex;align-items:center;gap:8px;flex-shrink:0; }
        .nb-avatar{
            width:32px;height:32px;border-radius:50%;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:12px;font-weight:800;
            box-shadow:0 2px 8px rgba(37,99,235,.22);flex-shrink:0;
        }
        .nb-name{ font-size:12.5px;font-weight:600;color:#374151;white-space:nowrap; }
        .nb-logout{
            font-size:12px;font-weight:700;color:#ef4444;
            background:#fff1f2;border:1.5px solid #fecdd3;
            padding:5px 12px;border-radius:9px;cursor:pointer;
            transition:background .15s;white-space:nowrap;
        }
        .nb-logout:hover{ background:#ffe4e6; }

        /* ══ MOBILE ══ */
        @media(max-width:768px){
            #nb-wrap{ padding:8px 12px 0; }
            #nb-shell{ padding:9px 13px; }
            .nb-row{ flex-direction:column;align-items:stretch;gap:8px; }
            .nb-links{
                overflow-x:auto;-webkit-overflow-scrolling:touch;
                scrollbar-width:none;gap:5px;padding-bottom:1px;
            }
            .nb-links::-webkit-scrollbar{ display:none; }
            .nb-links.hide-inactive a:not(.nb-active){
                max-width:0;opacity:0;
                padding-left:0;padding-right:0;
                margin:0;border-width:0;pointer-events:none;
            }
            .nb-user{ justify-content:space-between;width:100%; }
            .nb-name{ display:none; }
        }
        @media(max-width:400px){
            .nb-links a{ font-size:11.5px;padding:6px 10px; }
            .nb-logout{ font-size:11.5px;padding:5px 10px; }
        }

        /* ══ LAYOUT ══ */
        .page-header{ max-width:80rem;margin:14px auto 0;padding:0 16px; }
        .page-header-inner{
            background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;
            box-shadow:0 2px 12px rgba(15,23,42,.05);padding:14px 18px;
        }
        .page-main{
            max-width:80rem;margin:14px auto 24px;padding:0 16px;
            display:flex;flex-direction:column;gap:14px;
        }
    </style>
</head>
<body>

    <!-- ══ NAVBAR ══ -->
    <div id="nb-wrap">
        <div id="nb-shell">
            <div class="nb-row">

                @php $role = Auth::user()->role ?? null; @endphp

                <!-- LINKS -->
                <nav class="nb-links" id="nb-links">
                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'nb-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.data-warga') }}"
                           class="{{ request()->routeIs('admin.data-warga') ? 'nb-active' : '' }}">Data Warga</a>
                        <a href="{{ route('admin.data-akun') }}"
                           class="{{ request()->routeIs('admin.data-akun') ? 'nb-active' : '' }}">Data Akun</a>
                        <a href="{{ route('admin.filterisasi') }}"
                           class="{{ request()->routeIs('admin.filterisasi') ? 'nb-active' : '' }}">Filterisasi</a>
                        <a href="{{ route('admin.laporan') }}"
                           class="{{ request()->routeIs('admin.laporan') ? 'nb-active' : '' }}">Laporan</a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'nb-active' : '' }}">Dashboard</a>
                        <a href="{{ route('rt.calon-penerima.create') }}"
                           class="{{ request()->routeIs('rt.calon-penerima.create') ? 'nb-active' : '' }}">Pendataan</a>
                        <a href="{{ route('rt.calon-penerima.index') }}"
                           class="{{ request()->routeIs('rt.calon-penerima.index') ? 'nb-active' : '' }}">Riwayat</a>
                        <a href="{{ route('rt.laporan.index') }}"
                           class="{{ request()->routeIs('rt.laporan.*') ? 'nb-active' : '' }}">Laporan</a>
                    @endif
                </nav>

                <!-- USER -->
                <div class="nb-user">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="nb-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <span class="nb-name">{{ Auth::user()->name ?? '' }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nb-logout">Keluar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- HEADER SLOT -->
    @isset($header)
        <div class="page-header">
            <div class="page-header-inner">{{ $header }}</div>
        </div>
    @endisset

    <!-- MAIN -->
    <main class="page-main">{{ $slot }}</main>

    <script>
    (function(){
        var wrap  = document.getElementById('nb-wrap');
        var links = document.getElementById('nb-links');

        /* ── scrolled class: IntersectionObserver, zero reflow ── */
        var sentinel = document.createElement('div');
        sentinel.style.cssText = 'position:absolute;top:1px;left:0;width:1px;height:1px;pointer-events:none;';
        document.body.prepend(sentinel);
        new IntersectionObserver(function(e){
            e[0].isIntersecting
                ? wrap.classList.remove('scrolled')
                : wrap.classList.add('scrolled');
        }, {threshold:0}).observe(sentinel);

        /*
         * ── hide-inactive: DESKTOP ONLY ──
         *
         * Di mobile (≤768px) fitur ini dimatikan total karena:
         *  - Konten halaman pendek → scrollY sering jitter di bawah
         *  - iOS rubber-band / Android overscroll bounce bikin scrollY
         *    naik-turun ±2-5px meski jari tidak bergerak → class toggle
         *    bolak-balik tiap frame → kedip/getar
         *  - Di mobile sudah ada horizontal scroll untuk nav links,
         *    jadi collapse tidak diperlukan
         */
        var MOBILE_BP    = 768;

        var lastY        = window.scrollY;
        var peakY        = window.scrollY;
        var hidden       = false;
        var rafPending   = null;

        var DEAD_ZONE    = 8;
        var UP_THRESHOLD = 32;
        var BOTTOM_GUARD = 12;

        function isMobile(){ return window.innerWidth <= MOBILE_BP; }

        function maxScroll(){
            return Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
        }

        function applyHide(val){
            if(val === hidden) return;
            hidden = val;
            if(val) links.classList.add('hide-inactive');
            else    links.classList.remove('hide-inactive');
        }

        function tick(){
            rafPending = null;

            /* Mobile: selalu tampilkan semua link, jangan collapse */
            if(isMobile()){
                applyHide(false);
                lastY = window.scrollY;
                peakY = window.scrollY;
                return;
            }

            var y    = window.scrollY;
            var diff = y - lastY;

            if(y > peakY) peakY = y;

            /* Bottom guard */
            if(y >= maxScroll() - BOTTOM_GUARD){
                if(y > 50) applyHide(true);
                lastY = y;
                return;
            }

            /* Dead zone */
            if(Math.abs(diff) < DEAD_ZONE){
                lastY = y;
                return;
            }

            if(diff > 0 && y > 50){
                applyHide(true);
            } else if(diff < 0 && hidden){
                if(peakY - y >= UP_THRESHOLD){
                    applyHide(false);
                    peakY = y;
                }
            }

            lastY = y;
        }

        window.addEventListener('scroll', function(){
            if(rafPending) return;
            rafPending = requestAnimationFrame(tick);
        }, {passive:true});

        /* Resize: kalau user putar layar dari landscape ke portrait, reset state */
        window.addEventListener('resize', function(){
            if(isMobile()) applyHide(false);
        }, {passive:true});

    })();
    </script>
</body>
</html>