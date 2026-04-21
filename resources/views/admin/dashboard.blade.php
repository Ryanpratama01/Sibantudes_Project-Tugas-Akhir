<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:900;color:#0f172a;letter-spacing:-.02em;">Dashboard Kelurahan</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">Ringkasan data kelayakan penerima BLT-DD</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                {{-- BELL --}}
                <div style="position:relative;" id="notif-wrap">
                    <button onclick="toggleNotif()"
                            style="position:relative;width:36px;height:36px;background:#fff;border:1.5px solid #e2e8f0;border-radius:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.05);">
                        <svg width="16" height="16" fill="none" stroke="#64748b" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.976A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notif-badge"
                              style="display:none;position:absolute;top:-5px;right:-5px;min-width:17px;height:17px;border-radius:99px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;align-items:center;justify-content:center;padding:0 3px;border:2px solid #fff;">0</span>
                    </button>
                    <div id="notif-dropdown"
                         style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:min(310px,90vw);background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 20px 56px rgba(0,0,0,.13);z-index:999;overflow:hidden;">
                        <div style="padding:12px 15px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:12px;font-weight:800;color:#0f172a;">Notifikasi</span>
                            <button onclick="clearNotifs()" style="font-size:10.5px;color:#2563eb;cursor:pointer;font-weight:700;background:none;border:none;">Hapus semua</button>
                        </div>
                        <div id="notif-list" style="max-height:260px;overflow-y:auto;">
                            <div style="padding:24px 16px;text-align:center;font-size:12px;color:#94a3b8;">Tidak ada notifikasi</div>
                        </div>
                    </div>
                </div>

                {{-- CLOCK --}}
                <div style="display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid #e2e8f0;border-radius:11px;padding:7px 12px;font-size:11px;color:#64748b;font-weight:500;">
                    <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
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
        .tt thead th{padding:10px 12px;text-align:left;font-size:9.5px;font-weight:800;color:#94a3b8;white-space:nowrap;}
        .tt tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        .tt tbody tr:hover{background:#f8fbff;}
        .tt tbody td{padding:10px 12px;font-size:12px;color:#374151;}

        /* notif items */
        .ni{display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-bottom:1px solid #f8fafc;}
        .ni.unread{background:#f0f7ff;}
        .ni-icon{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .ni-icon svg{width:14px;height:14px;}
        .ni-txt{font-size:11.5px;font-weight:600;color:#1e293b;line-height:1.4;}
        .ni-time{font-size:10px;color:#94a3b8;margin-top:2px;}

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .adm-stat-grid  { grid-template-columns: repeat(2,1fr) !important; }
            .adm-chart-grid { grid-template-columns: 1fr !important; }
        }
        @media (max-width: 600px) {
            .adm-stat-grid  { grid-template-columns: repeat(2,1fr) !important; }
            .adm-greeting   { flex-direction: column !important; align-items: flex-start !important; }
            #admin-clock    { display: none; }
        }
        @media (max-width: 400px) {
            .adm-stat-grid  { grid-template-columns: 1fr !important; }
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
            if(!NS.items.length){list.innerHTML='<div style="padding:24px 16px;text-align:center;font-size:12px;color:#94a3b8;">Tidak ada notifikasi</div>';return;}
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

    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- GREETING --}}
        <div class="adm-greeting" style="display:flex;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1.5px solid #f1f5f9;border-radius:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(0,0,0,.04);flex:1;min-width:220px;">
                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:800;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,.28);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0f172a;">Selamat datang, <span style="color:#2563eb;">{{ Auth::user()->name ?? 'Admin' }}</span> 👋</p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">Panel admin — kelola dan pantau seluruh data warga.</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:16px;padding:14px 16px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.976A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#92400e;">{{ $totalPending ?? 0 }} data menunggu verifikasi</p>
                    <a href="{{ route('admin.data-warga') }}" style="font-size:11px;color:#b45309;text-decoration:none;font-weight:600;">Proses sekarang →</a>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="adm-stat-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">

            @php
                $cards = [
                    ['label'=>'Total Warga','sub'=>'Dari semua dusun','val'=>$totalWarga??0,'grad'=>'#2563eb,#1d4ed8','shadow'=>'rgba(37,99,235,.3)','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','tc'=>'rgba(191,219,254,.9)'],
                    ['label'=>'Pending','sub'=>'Belum diproses','val'=>$totalPending??0,'grad'=>'#f59e0b,#d97706','shadow'=>'rgba(245,158,11,.3)','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(254,243,199,.9)'],
                    ['label'=>'Diterima','sub'=>'Sudah ditetapkan','val'=>$totalPenerima??0,'grad'=>'#10b981,#059669','shadow'=>'rgba(16,185,129,.3)','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(167,243,208,.9)'],
                    ['label'=>'Ditolak','sub'=>'Tidak memenuhi syarat','val'=>$totalDitolak??0,'grad'=>'#f43f5e,#e11d48','shadow'=>'rgba(244,63,94,.3)','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z','tc'=>'rgba(254,205,211,.9)'],
                ];
            @endphp

            @foreach($cards as $c)
            <div style="position:relative;background:linear-gradient(135deg,{{ $c['grad'] }});color:#fff;border-radius:16px;padding:16px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px {{ $c['shadow'] }};">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10px;font-weight:600;color:{{ $c['tc'] }};">{{ $c['label'] }}</p>
                    <p style="font-size:28px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $c['val'] }}</p>
                    <p style="font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;">{{ $c['sub'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:40px;height:40px;background:rgba(255,255,255,.18);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/></svg>
                </div>
            </div>
            @endforeach

        </div>

        {{-- GRAFIK + DATA PER DUSUN --}}
        <div class="adm-chart-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:14px;">

            {{-- Grafik --}}
            <div class="db-card">
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
                    <div style="display:flex;align-items:center;gap:7px;">
                        <div style="width:3px;height:15px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;"></div>
                        <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Warga per Dusun</h3>
                    </div>
                    <span style="font-size:10.5px;color:#94a3b8;background:#fff;border:1px solid #f1f5f9;padding:2px 9px;border-radius:20px;font-weight:600;">
                        {{ $wargaPerDusun->sum('total') }} total
                    </span>
                </div>
                <div style="padding:16px;overflow-x:auto;">
                    @php
                        $dusuns=$wargaPerDusun->values();
                        $n=max($dusuns->count(),1);
                        $maxV=max((int)$dusuns->max('total'),1);
                        $W=560;$H=200;$pL=40;$pR=20;$pT=28;$pB=44;
                        $cW=$W-$pL-$pR;$cH=$H-$pT-$pB;
                        $step=max(1,(int)ceil($maxV/4));
                        $yTicks=range(0,$maxV+$step,$step);
                        $pts=[];
                        foreach($dusuns as $i=>$d){
                            $x=$pL+($n===1?$cW/2:($i/($n-1))*$cW);
                            $y=$pT+$cH-($d->total/$maxV)*$cH;
                            $pts[]=['x'=>round($x,1),'y'=>round($y,1),'val'=>$d->total,'name'=>$d->dusun];
                        }
                        $poly=implode(' ',array_map(fn($p)=>"{$p['x']},{$p['y']}",$pts));
                        $firstPt=$pts[0];$lastPt=$pts[count($pts)-1];
                        $botY=$pT+$cH;
                        $areaPath="M {$firstPt['x']},{$firstPt['y']} ".implode(' ',array_map(fn($p)=>"L {$p['x']},{$p['y']}",array_slice($pts,1)))." L {$lastPt['x']},{$botY} L {$firstPt['x']},{$botY} Z";
                    @endphp
                    <svg viewBox="0 0 {{ $W }} {{ $H }}" width="100%" style="min-width:260px;" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="fg" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#3b82f6" stop-opacity=".18"/>
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        @foreach($yTicks as $t)
                            @if($t<=$maxV+$step)
                                @php $gy=$pT+$cH-min($t,$maxV)/$maxV*$cH; @endphp
                                <line x1="{{ $pL }}" y1="{{ $gy }}" x2="{{ $W-$pR }}" y2="{{ $gy }}" stroke="#f1f5f9" stroke-width="1"/>
                                <text x="{{ $pL-5 }}" y="{{ $gy+4 }}" text-anchor="end" font-size="10" fill="#94a3b8">{{ $t }}</text>
                            @endif
                        @endforeach
                        <line x1="{{ $pL }}" y1="{{ $botY }}" x2="{{ $W-$pR }}" y2="{{ $botY }}" stroke="#cbd5e1" stroke-width="1.5"/>
                        <line x1="{{ $pL }}" y1="{{ $pT }}" x2="{{ $pL }}" y2="{{ $botY }}" stroke="#cbd5e1" stroke-width="1.5"/>
                        <path d="{{ $areaPath }}" fill="url(#fg)"/>
                        <polyline points="{{ $poly }}" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                        @foreach($pts as $p)
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5" fill="white" stroke="#2563eb" stroke-width="2.5"/>
                            <text x="{{ $p['x'] }}" y="{{ $p['y']-10 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#2563eb">{{ $p['val'] }}</text>
                            <text x="{{ $p['x'] }}" y="{{ $botY+15 }}" text-anchor="middle" font-size="10" fill="#64748b">{{ Str::limit($p['name'],8) }}</text>
                        @endforeach
                    </svg>
                </div>
            </div>

            {{-- Data per dusun --}}
            <div class="db-card">
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;gap:7px;">
                    <div style="width:3px;height:15px;background:linear-gradient(180deg,#10b981,#059669);border-radius:4px;"></div>
                    <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Per Dusun</h3>
                </div>
                <div style="padding:12px;display:flex;flex-direction:column;gap:8px;">
                    @php $maxDusun=$wargaPerDusun->max('total')?:1; @endphp
                    @foreach($wargaPerDusun as $data)
                    <div style="padding:10px 12px;background:#f8fafc;border:1.5px solid #f1f5f9;border-radius:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                            <span style="font-size:12px;font-weight:700;color:#1e293b;">{{ $data->dusun }}</span>
                            <span style="font-size:14px;font-weight:900;color:#2563eb;">{{ $data->total }}</span>
                        </div>
                        <div style="width:100%;height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                            <div style="height:100%;background:linear-gradient(90deg,#3b82f6,#2563eb);border-radius:99px;width:{{ ($data->total/$maxDusun)*100 }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- TOP 10 --}}
        <div class="db-card tt">
            <div style="padding:12px 16px;border-bottom:1.5px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:3px;height:16px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;"></div>
                    <div>
                        <h3 style="font-size:13px;font-weight:800;color:#0f172a;">Top 10 Probabilitas Tertinggi</h3>
                        <p style="font-size:10px;color:#94a3b8;margin-top:1px;">Diurutkan dari probabilitas terbesar</p>
                    </div>
                </div>
                <a href="{{ route('admin.filterisasi') }}"
                   style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;padding:5px 12px;border-radius:9px;text-decoration:none;">
                    Lihat Semua
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                            <th style="min-width:140px;">Probabilitas</th>
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
                                <div style="width:24px;height:24px;border-radius:7px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:{{ $tc }};">{{ $index+1 }}</div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:800;color:#fff;">
                                        {{ strtoupper(substr($warga->nama,0,1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:12px;font-weight:700;color:#0f172a;white-space:nowrap;">{{ $warga->nama }}</div>
                                        <div style="font-size:10px;color:#94a3b8;">{{ $warga->pekerjaan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span style="font-family:monospace;font-size:10.5px;color:#6b7280;background:#f3f4f6;padding:2px 7px;border-radius:6px;white-space:nowrap;">{{ $warga->nik }}</span></td>
                            <td>
                                <div style="white-space:nowrap;">
                                    <span style="display:inline-block;padding:2px 8px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10.5px;font-weight:700;border:1px solid #bfdbfe;">{{ $warga->dusun }}</span>
                                    <span style="font-size:10.5px;color:#64748b;font-weight:600;margin-left:4px;">RT {{ str_pad($warga->rt??'0',3,'0',STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td style="white-space:nowrap;font-weight:600;">Rp {{ number_format($warga->penghasilan,0,',','.') }}</td>
                            <td>
                                @php $sc=$warga->probabilitas>=70?'#10b981':($warga->probabilitas>=40?'#f59e0b':'#f43f5e'); @endphp
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <div style="flex:1;background:#f1f5f9;border-radius:99px;height:5px;overflow:hidden;min-width:70px;">
                                        <div style="height:100%;border-radius:99px;background:{{ $sc }};width:{{ min($warga->probabilitas,100) }}%;"></div>
                                    </div>
                                    <span style="font-size:11.5px;font-weight:800;color:{{ $sc }};white-space:nowrap;">{{ number_format($warga->probabilitas,1) }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $st=['pending'=>['#fffbeb','#b45309','#fde68a','Pending'],'diterima'=>['#f0fdf4','#166534','#bbf7d0','Diterima'],'ditolak'=>['#fff1f2','#be123c','#fecdd3','Ditolak']];
                                    $s=$st[$warga->status]??['#eff6ff','#1d4ed8','#bfdbfe',ucfirst($warga->status)];
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 9px;font-size:10.5px;font-weight:700;border-radius:20px;background:{{ $s[0] }};color:{{ $s[1] }};border:1px solid {{ $s[2] }};white-space:nowrap;">
                                    <span style="width:5px;height:5px;border-radius:50%;background:{{ $s[1] }};flex-shrink:0;"></span>{{ $s[3] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">Belum ada data warga</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>