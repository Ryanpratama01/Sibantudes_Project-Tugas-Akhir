<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-.02em;">Dashboard Admin Perangkat Desa</h2>
                <p style="font-size:14px;color:#64748b;margin-top:4px;font-weight:500;">Ringkasan data kelayakan penerima BLT-DD</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                {{-- BELL --}}
                <div style="position:relative;" id="notif-wrap">
                    <button onclick="toggleNotif()"
                            style="position:relative;width:44px;height:44px;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.05);">
                        <svg width="20" height="20" fill="none" stroke="#64748b" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.976A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notif-badge"
                              style="display:none;position:absolute;top:-6px;right:-6px;min-width:20px;height:20px;border-radius:99px;background:#ef4444;color:#fff;font-size:11px;font-weight:800;align-items:center;justify-content:center;padding:0 4px;border:2px solid #fff;">0</span>
                    </button>
                    <div id="notif-dropdown"
                         style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:min(340px,92vw);background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 20px 56px rgba(0,0,0,.13);z-index:999;overflow:hidden;">
                        <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:15px;font-weight:800;color:#0f172a;">Notifikasi</span>
                            <button onclick="clearNotifs()" style="font-size:14px;color:#2563eb;cursor:pointer;font-weight:700;background:none;border:none;">Hapus semua</button>
                        </div>
                        <div id="notif-list" style="max-height:300px;overflow-y:auto;">
                            <div style="padding:28px 18px;text-align:center;font-size:14px;color:#94a3b8;">Tidak ada notifikasi</div>
                        </div>
                    </div>
                </div>

                {{-- CLOCK --}}
                <div id="admin-clock-wrap" style="display:inline-flex;align-items:center;gap:8px;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:10px 16px;font-size:13px;color:#64748b;font-weight:600;">
                    <svg width="16" height="16" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="admin-clock"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        /* ── SHARED ── */
        .db-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.04);overflow:hidden;}
        .sc-blob1{position:absolute;top:-16px;right:-16px;width:80px;height:80px;background:rgba(255,255,255,.12);border-radius:50%;pointer-events:none;}
        .sc-blob2{position:absolute;bottom:-20px;right:8px;width:56px;height:56px;background:rgba(255,255,255,.09);border-radius:50%;pointer-events:none;}

        /* ── TABLE ── */
        .tt table{width:100%;border-collapse:collapse;}
        .tt thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        .tt thead th{padding:13px 14px;text-align:left;font-size:12px;font-weight:800;color:#64748b;white-space:nowrap;}
        .tt tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        .tt tbody tr:hover{background:#f8fbff;}
        .tt tbody td{padding:13px 14px;font-size:14px;color:#374151;}

        /* notif items */
        .ni{display:flex;align-items:flex-start;gap:12px;padding:13px 16px;border-bottom:1px solid #f8fafc;}
        .ni.unread{background:#f0f7ff;}
        .ni-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .ni-icon svg{width:17px;height:17px;}
        .ni-txt{font-size:14px;font-weight:600;color:#1e293b;line-height:1.5;}
        .ni-time{font-size:12px;color:#94a3b8;margin-top:3px;}

        /* ── RESPONSIVE DESKTOP ── */
        @media (max-width: 1024px) {
            .adm-stat-grid  { grid-template-columns: repeat(2,1fr) !important; }
            .adm-chart-grid { grid-template-columns: 1fr !important; }
        }

        /* ── RESPONSIVE MOBILE ── */
        @media (max-width: 768px) {
            /* Header */
            .adm-header-title  { font-size:17px !important; }
            .adm-header-sub    { font-size:13px !important; }

            /* Jam disembunyikan di mobile */
            #admin-clock-wrap { display:none !important; }

            /* Notif button lebih mudah dipencet */
            #notif-wrap button { width:48px !important; height:48px !important; }

            /* Stat cards: 2 kolom proporsional */
            .adm-stat-grid {
                grid-template-columns: repeat(2,1fr) !important;
                gap:10px !important;
            }
            .adm-stat-grid > div {
                padding:16px 14px !important;
                border-radius:14px !important;
            }
            .adm-stat-card-num  { font-size:30px !important; line-height:1 !important; }
            .adm-stat-card-lbl  { font-size:13px !important; }
            .adm-stat-card-sub  { font-size:11px !important; }
            .adm-stat-card-icon { width:44px !important; height:44px !important; }
            .adm-stat-card-icon svg { width:22px !important; height:22px !important; }

            /* Greeting: susun vertikal, full width */
            .adm-greeting { flex-direction:column !important; gap:10px !important; }
            .adm-greeting > div {
                min-width:unset !important;
                flex:none !important;
                width:100% !important;
            }
            .adm-greeting-name { font-size:15px !important; }
            .adm-greeting-sub  { font-size:13px !important; }
            .adm-pending-txt   { font-size:14px !important; }
            .adm-pending-link  { font-size:13px !important; }

            /* Chart: stack vertikal */
            .adm-chart-grid {
                grid-template-columns:1fr !important;
                gap:12px !important;
            }
            .adm-section-title { font-size:15px !important; }
            .adm-section-badge { font-size:13px !important; }

            /* Dusun bar */
            .adm-dusun-name { font-size:14px !important; }
            .adm-dusun-num  { font-size:18px !important; }
            .adm-dusun-bar  { height:8px !important; }

            /* Top 10 table */
            .tt thead th {
                font-size:13px !important;
                padding:12px 14px !important;
                white-space:nowrap !important;
            }
            .tt tbody td {
                font-size:13px !important;
                padding:12px 14px !important;
            }
            .adm-tbl-name   { font-size:14px !important; }
            .adm-tbl-job    { font-size:12px !important; }
            .adm-tbl-nik    { font-size:12px !important; }
            .adm-tbl-dusun  { font-size:12px !important; }
            .adm-tbl-rt     { font-size:12px !important; }
            .adm-tbl-income { font-size:13px !important; }
            .adm-tbl-pct    { font-size:13px !important; }
            .adm-tbl-badge  { font-size:12px !important; padding:6px 12px !important; }
            .adm-rank-badge { width:32px !important; height:32px !important; font-size:13px !important; }
            .adm-avatar-sm  { width:34px !important; height:34px !important; font-size:14px !important; }

            /* Tombol Lihat Semua */
            .adm-lihat-semua {
                font-size:13px !important;
                padding:9px 14px !important;
                min-height:42px !important;
            }
        }

        @media (max-width: 420px) {
            /* HP sangat kecil: stat cards tetap 2 kolom, lebih compact */
            .adm-stat-grid { grid-template-columns: repeat(2,1fr) !important; gap:8px !important; }
            .adm-stat-card-num { font-size:26px !important; }
            .adm-stat-grid > div { padding:14px 12px !important; }
        }
    </style>

    <script>
        /* Clock */
        function updateAdminClock(){
            const now=new Date();
            const d=now.toLocaleDateString('id-ID',{timeZone:'Asia/Jakarta',weekday:'short',day:'2-digit',month:'short',year:'numeric'});
            const t=now.toLocaleTimeString('id-ID',{timeZone:'Asia/Jakarta',hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
            const el=document.getElementById('admin-clock');
            if(el) el.textContent=d+' '+t+' WIB';
        }
        updateAdminClock();
        setInterval(updateAdminClock,1000);

        /* Notifikasi */
        const _nk='sbd_notifs_v1';
        const NS={
            items:(()=>{try{return JSON.parse(localStorage.getItem(_nk)||'[]');}catch(e){return [];}}()),
            save(){try{localStorage.setItem(_nk,JSON.stringify(this.items));}catch(e){}},
            add(icon,color,text){
                this.items.unshift({id:Date.now(),icon,color,text,time:new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}),read:false});
                if(this.items.length>30)this.items=this.items.slice(0,30);
                this.save();renderNotifs();
            },
            markAllRead(){this.items.forEach(i=>i.read=true);this.save();},
            clearAll(){this.items=[];this.save();},
            unread(){return this.items.filter(i=>!i.read).length;}
        };
        function renderNotifs(){
            const list=document.getElementById('notif-list');
            const badge=document.getElementById('notif-badge');
            if(!list)return;
            const c=NS.unread();
            if(badge){badge.textContent=c>9?'9+':c;badge.style.display=c>0?'flex':'none';}
            if(!NS.items.length){list.innerHTML='<div style="padding:28px 18px;text-align:center;font-size:14px;color:#94a3b8;">Tidak ada notifikasi</div>';return;}
            list.innerHTML=NS.items.map(n=>`<div class="ni ${n.read?'':'unread'}"><div class="ni-icon" style="background:${n.color}18;"><svg fill="none" stroke="${n.color}" viewBox="0 0 24 24">${n.icon}</svg></div><div><div class="ni-txt">${n.text}</div><div class="ni-time">${n.time}</div></div></div>`).join('');
        }
        function toggleNotif(){
            const dd=document.getElementById('notif-dropdown');
            if(!dd)return;
            const opening=dd.style.display==='none';
            dd.style.display=opening?'block':'none';
            if(opening){NS.markAllRead();renderNotifs();}
        }
        function clearNotifs(){NS.clearAll();renderNotifs();}

        let _lw={{$totalWarga??0}},_lp={{$totalPending??0}},_ln={{$totalPenerima??0}};
        async function pollStats(){
            try{
                const res=await fetch(window.location.href,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
                if(!res.ok||!res.headers.get('content-type')?.includes('json'))return;
                const d=await res.json();
                if((d.totalWarga??0)>_lw){NS.add('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>','#2563eb',`${d.totalWarga-_lw} data warga baru`);_lw=d.totalWarga;}
                if((d.totalPending??0)>_lp){NS.add('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>','#f59e0b',`${d.totalPending-_lp} data baru menunggu verifikasi`);_lp=d.totalPending;}
                if((d.totalPenerima??0)>_ln){NS.add('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>','#10b981',`${d.totalPenerima-_ln} warga baru ditetapkan`);_ln=d.totalPenerima;}
            }catch(e){}
        }
        document.addEventListener('DOMContentLoaded',()=>{
            renderNotifs();
            setInterval(pollStats,30000);
            document.addEventListener('click',e=>{
                const w=document.getElementById('notif-wrap');
                if(w&&!w.contains(e.target)){const dd=document.getElementById('notif-dropdown');if(dd)dd.style.display='none';}
            });
        });
    </script>

    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- GREETING --}}
        <div class="adm-greeting" style="display:flex;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:14px;background:#fff;border:1.5px solid #f1f5f9;border-radius:16px;padding:16px 20px;box-shadow:0 1px 4px rgba(0,0,0,.04);flex:1;min-width:240px;">
                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:800;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,.28);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="adm-greeting-name" style="font-size:16px;font-weight:700;color:#0f172a;">Selamat datang, <span style="color:#2563eb;">{{ Auth::user()->name ?? 'Admin' }}</span> 👋</p>
                    <p class="adm-greeting-sub" style="font-size:13px;color:#64748b;margin-top:4px;font-weight:500;">Panel admin — kelola dan pantau seluruh data warga.</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:14px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:16px;padding:16px 20px;">
                <div style="width:44px;height:44px;border-radius:50%;background:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.976A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <p class="adm-pending-txt" style="font-size:15px;font-weight:700;color:#92400e;">{{ $totalPending ?? 0 }} data menunggu verifikasi</p>
                    <a href="{{ route('admin.data-warga') }}" class="adm-pending-link" style="font-size:13px;color:#b45309;text-decoration:none;font-weight:600;">Proses sekarang →</a>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="adm-stat-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">

            @php
                $cards = [
                    ['label'=>'Total Warga','sub'=>'Dari semua dusun','val'=>$totalWarga??0,'grad'=>'#2563eb,#1d4ed8','shadow'=>'rgba(37,99,235,.3)','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','tc'=>'rgba(191,219,254,.9)'],
                    ['label'=>'Pending','sub'=>'Belum diproses','val'=>$totalPending??0,'grad'=>'#f59e0b,#d97706','shadow'=>'rgba(245,158,11,.3)','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(254,243,199,.9)'],
                    ['label'=>'Diterima','sub'=>'Sudah ditetapkan','val'=>$totalPenerima??0,'grad'=>'#10b981,#059669','shadow'=>'rgba(16,185,129,.3)','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(167,243,208,.9)'],
                    ['label'=>'Ditolak','sub'=>'Tidak memenuhi syarat','val'=>$totalDitolak??0,'grad'=>'#f43f5e,#e11d48','shadow'=>'rgba(244,63,94,.3)','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(254,205,211,.9)'],
                ];
            @endphp

            @foreach($cards as $c)
            <div style="position:relative;background:linear-gradient(135deg,{{ $c['grad'] }});color:#fff;border-radius:18px;padding:20px 18px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px {{ $c['shadow'] }};">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p class="adm-stat-card-lbl" style="font-size:13px;font-weight:700;color:{{ $c['tc'] }};">{{ $c['label'] }}</p>
                    <p class="adm-stat-card-num" style="font-size:34px;font-weight:900;margin-top:4px;line-height:1;letter-spacing:-.04em;">{{ $c['val'] }}</p>
                    <p class="adm-stat-card-sub" style="font-size:12px;color:rgba(255,255,255,.7);margin-top:4px;">{{ $c['sub'] }}</p>
                </div>
                <div class="adm-stat-card-icon" style="position:relative;z-index:1;width:50px;height:50px;background:rgba(255,255,255,.18);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="26" height="26" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/></svg>
                </div>
            </div>
            @endforeach

        </div>

        {{-- GRAFIK + DATA PER DUSUN --}}
        <div class="adm-chart-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:16px;">

            {{-- Grafik Modern --}}
            <div class="db-card" style="position:relative;">

                {{-- Header --}}
                <div style="padding:16px 20px 14px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#2563eb);box-shadow:0 0 0 3px rgba(37,99,235,.15);flex-shrink:0;"></div>
                            <h3 class="adm-section-title" style="font-size:15px;font-weight:800;color:#0f172a;">Warga per Dusun</h3>
                        </div>
                        <p style="font-size:12px;color:#94a3b8;margin-top:3px;margin-left:18px;">Distribusi warga di setiap dusun</p>
                    </div>
                    <div style="text-align:right;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:8px 14px;">
                        <p style="font-size:20px;font-weight:900;color:#1d4ed8;line-height:1;">{{ $wargaPerDusun->sum('total') }}</p>
                        <p style="font-size:11px;color:#60a5fa;font-weight:600;">total warga</p>
                    </div>
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 20%,#e2e8f0 80%,transparent);margin:0 16px;"></div>

                {{-- SVG Chart --}}
                <div style="padding:16px 12px 4px;position:relative;">
                    @php
                        $dusuns = $wargaPerDusun->values();
                        $n      = max($dusuns->count(), 1);
                        $maxV   = max((int)$dusuns->max('total'), 1);

                        $W=360; $H=190; $pL=36; $pR=12; $pT=30; $pB=46;
                        $cW=$W-$pL-$pR; $cH=$H-$pT-$pB;
                        $botY=$pT+$cH;

                        $pts=[];
                        foreach($dusuns as $i=>$d){
                            $x = round($pL + ($n===1 ? $cW/2 : ($i/($n-1))*$cW), 1);
                            $y = round($pT + $cH - ($d->total / $maxV) * $cH, 1);
                            $pts[] = ['x'=>$x,'y'=>$y,'val'=>$d->total,'name'=>$d->dusun];
                        }

                        /* Smooth cubic bezier */
                        $linePath = "M {$pts[0]['x']},{$pts[0]['y']}";
                        for($i=0;$i<count($pts)-1;$i++){
                            $cp1x = round($pts[$i]['x'] + ($pts[$i+1]['x']-$pts[$i]['x'])*0.45, 1);
                            $cp2x = round($pts[$i+1]['x'] - ($pts[$i+1]['x']-$pts[$i]['x'])*0.45, 1);
                            $linePath .= " C {$cp1x},{$pts[$i]['y']} {$cp2x},{$pts[$i+1]['y']} {$pts[$i+1]['x']},{$pts[$i+1]['y']}";
                        }
                        $areaPath = $linePath." L {$pts[count($pts)-1]['x']},{$botY} L {$pts[0]['x']},{$botY} Z";

                        $step   = max(1,(int)ceil($maxV/4));
                        $yTicks = range(0, $maxV, $step);
                    @endphp

                    <svg viewBox="0 0 {{ $W }} {{ $H }}" width="100%" style="display:block;overflow:visible;" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%"   stop-color="#3b82f6" stop-opacity=".25"/>
                                <stop offset="75%"  stop-color="#3b82f6" stop-opacity=".05"/>
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                            </linearGradient>
                            <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%"   stop-color="#60a5fa"/>
                                <stop offset="100%" stop-color="#1d4ed8"/>
                            </linearGradient>
                            <filter id="glow">
                                <feGaussianBlur stdDeviation="2" result="b"/>
                                <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                            </filter>
                            <clipPath id="cClip">
                                <rect x="{{ $pL }}" y="{{ $pT-2 }}" width="{{ $cW }}" height="{{ $cH+4 }}"/>
                            </clipPath>
                        </defs>

                        {{-- Grid garis datar dashed --}}
                        @foreach($yTicks as $t)
                            @php $gy = round($pT + $cH - ($t/$maxV)*$cH, 1); @endphp
                            <line x1="{{ $pL }}" y1="{{ $gy }}" x2="{{ $W-$pR }}" y2="{{ $gy }}"
                                  stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4,5"/>
                            <text x="{{ $pL-5 }}" y="{{ $gy+4 }}" text-anchor="end"
                                  font-size="10" fill="#cbd5e1" font-weight="700"
                                  font-family="Figtree,sans-serif">{{ $t }}</text>
                        @endforeach

                        {{-- Baseline --}}
                        <line x1="{{ $pL }}" y1="{{ $botY }}" x2="{{ $W-$pR }}" y2="{{ $botY }}"
                              stroke="#e2e8f0" stroke-width="1.2"/>

                        {{-- Area fill --}}
                        <path d="{{ $areaPath }}" fill="url(#areaGrad)" clip-path="url(#cClip)"/>

                        {{-- Line smooth + glow --}}
                        {{-- Shadow line tipis di bawah --}}
                        <path d="{{ $linePath }}" fill="none" stroke="#bfdbfe"
                              stroke-width="5" stroke-linecap="round" opacity="0.4" clip-path="url(#cClip)"/>
                        {{-- Garis utama --}}
                        <path d="{{ $linePath }}" fill="none" stroke="url(#lineGrad)"
                              stroke-width="3" stroke-linecap="round" clip-path="url(#cClip)"/>

                        {{-- Titik data --}}
                        @foreach($pts as $idx => $p)
                            {{-- Ring luar --}}
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="10"
                                    fill="rgba(37,99,235,.08)"/>
                            {{-- Dot putih dengan border biru --}}
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5.5"
                                    fill="#fff" stroke="url(#lineGrad)" stroke-width="2.5"
                                    filter="url(#glow)"/>
                            {{-- Dot inner biru kecil --}}
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="2.5"
                                    fill="#2563eb"/>

                            {{-- Badge nilai di atas titik --}}
                            <rect x="{{ $p['x']-12 }}" y="{{ $p['y']-28 }}"
                                  width="24" height="16" rx="5"
                                  fill="#1d4ed8"/>
                            <text x="{{ $p['x'] }}" y="{{ $p['y']-17 }}" text-anchor="middle"
                                  font-size="10" font-weight="800" fill="#fff"
                                  font-family="Figtree,sans-serif">{{ $p['val'] }}</text>

                            {{-- Label dusun di bawah --}}
                            <text x="{{ $p['x'] }}" y="{{ $botY+14 }}" text-anchor="middle"
                                  font-size="10" fill="#64748b" font-weight="700"
                                  font-family="Figtree,sans-serif">{{ Str::limit($p['name'],7) }}</text>
                            <line x1="{{ $p['x'] }}" y1="{{ $botY+2 }}" x2="{{ $p['x'] }}" y2="{{ $botY+5 }}"
                                  stroke="#e2e8f0" stroke-width="1"/>
                        @endforeach
                    </svg>
                </div>

                {{-- Legend --}}
                <div style="padding:4px 20px 16px;display:flex;align-items:center;gap:8px;">
                    <div style="width:20px;height:3px;background:linear-gradient(90deg,#60a5fa,#1d4ed8);border-radius:99px;"></div>
                    <span style="font-size:12px;color:#94a3b8;font-weight:600;">Jumlah warga per dusun</span>
                </div>
            </div>

            {{-- Data per dusun --}}
            <div class="db-card">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;gap:9px;">
                    <div style="width:4px;height:18px;background:linear-gradient(180deg,#10b981,#059669);border-radius:4px;"></div>
                    <h3 class="adm-section-title" style="font-size:15px;font-weight:700;color:#1e293b;">Per Dusun</h3>
                </div>
                <div style="padding:14px;display:flex;flex-direction:column;gap:10px;">
                    @php $maxDusun=$wargaPerDusun->max('total')?:1; @endphp
                    @foreach($wargaPerDusun as $data)
                    <div style="padding:12px 14px;background:#f8fafc;border:1.5px solid #f1f5f9;border-radius:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <span class="adm-dusun-name" style="font-size:14px;font-weight:700;color:#1e293b;">{{ $data->dusun }}</span>
                            <span class="adm-dusun-num" style="font-size:17px;font-weight:900;color:#2563eb;">{{ $data->total }}</span>
                        </div>
                        <div class="adm-dusun-bar" style="width:100%;height:7px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                            <div style="height:100%;background:linear-gradient(90deg,#3b82f6,#2563eb);border-radius:99px;width:{{ ($data->total/$maxDusun)*100 }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- TOP 10 --}}
        <div class="db-card tt">
            <div style="padding:14px 18px;border-bottom:1.5px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:4px;height:18px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;"></div>
                    <div>
                        <h3 class="adm-section-title" style="font-size:15px;font-weight:800;color:#0f172a;">Top 10 Probabilitas Tertinggi</h3>
                        <p style="font-size:12px;color:#94a3b8;margin-top:3px;">Diurutkan dari probabilitas terbesar</p>
                    </div>
                </div>
                <a href="{{ route('admin.filterisasi') }}" class="adm-lihat-semua"
                   style="display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:700;color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;padding:10px 18px;border-radius:10px;text-decoration:none;min-height:44px;">
                    Lihat Semua
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Dusun / RT</th>
                            <th>Penghasilan</th>
                            <th style="min-width:160px;">Probabilitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topWarga as $index => $warga)
                        <tr>
                            <td>
                                @php
                                    $rk = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#94a3b8,#64748b)','linear-gradient(135deg,#f97316,#ea580c)'];
                                    $bg = $index < 3 ? $rk[$index] : '#f1f5f9';
                                    $tc = $index < 3 ? '#fff' : '#64748b';
                                @endphp
                                <div class="adm-rank-badge" style="width:30px;height:30px;border-radius:8px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:{{ $tc }};">{{ $index+1 }}</div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="adm-avatar-sm" style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;font-weight:800;color:#fff;">
                                        {{ strtoupper(substr($warga->nama,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="adm-tbl-name" style="font-size:14px;font-weight:700;color:#0f172a;white-space:nowrap;">{{ $warga->nama }}</div>
                                        <div class="adm-tbl-job" style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $warga->pekerjaan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="adm-tbl-nik" style="font-family:monospace;font-size:12px;color:#6b7280;background:#f3f4f6;padding:3px 9px;border-radius:6px;white-space:nowrap;">{{ $warga->nik }}</span></td>
                            <td>
                                <div style="white-space:nowrap;">
                                    <span class="adm-tbl-dusun" style="display:inline-block;padding:3px 10px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:700;border:1px solid #bfdbfe;">{{ $warga->dusun }}</span>
                                    <span class="adm-tbl-rt" style="font-size:12px;color:#64748b;font-weight:600;margin-left:5px;">RT {{ str_pad($warga->rt??'0',3,'0',STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td class="adm-tbl-income" style="white-space:nowrap;font-weight:600;font-size:14px;">Rp {{ number_format($warga->penghasilan,0,',','.') }}</td>
                            <td>
                                @php $sc=$warga->probabilitas>=70?'#10b981':($warga->probabilitas>=40?'#f59e0b':'#f43f5e'); @endphp
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="flex:1;background:#f1f5f9;border-radius:99px;height:7px;overflow:hidden;min-width:80px;">
                                        <div style="height:100%;border-radius:99px;background:{{ $sc }};width:{{ min($warga->probabilitas,100) }}%;"></div>
                                    </div>
                                    <span class="adm-tbl-pct" style="font-size:13px;font-weight:800;color:{{ $sc }};white-space:nowrap;">{{ number_format($warga->probabilitas,1) }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $st=['pending'=>['#fffbeb','#b45309','#fde68a','Pending'],'diterima'=>['#f0fdf4','#166534','#bbf7d0','Diterima'],'ditolak'=>['#fff1f2','#be123c','#fecdd3','Ditolak']];
                                    $s=$st[$warga->status]??['#eff6ff','#1d4ed8','#bfdbfe',ucfirst($warga->status)];
                                @endphp
                                <span class="adm-tbl-badge" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;font-size:12px;font-weight:700;border-radius:20px;background:{{ $s[0] }};color:{{ $s[1] }};border:1px solid {{ $s[2] }};white-space:nowrap;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:{{ $s[1] }};flex-shrink:0;"></span>{{ $s[3] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;font-size:15px;">Belum ada data warga</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>