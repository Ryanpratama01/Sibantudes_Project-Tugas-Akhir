<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerima BLT-DD — Desa Ngerong</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
            padding: 26px 30px;
        }

        /* ── Header ── */
        .hd { display: table; width: 100%; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 14px; }
        .hd-logo-cell { display: table-cell; vertical-align: middle; width: 48px; }
        .hd-logo { width: 40px; height: 40px; background: #1e3a8a; border-radius: 9px; text-align: center; line-height: 40px; font-size: 18px; font-weight: 900; color: #fff; }
        .hd-text-cell { display: table-cell; vertical-align: middle; padding-left: 11px; }
        .hd-title { font-size: 13px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: .02em; }
        .hd-sub { font-size: 8.5px; color: #64748b; margin-top: 2px; }
        .hd-meta-cell { display: table-cell; vertical-align: middle; text-align: right; width: 150px; }
        .hd-badge { display: inline-block; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; font-size: 7.5px; font-weight: 700; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: .05em; }
        .hd-date { font-size: 7.5px; color: #94a3b8; margin-top: 3px; }

        /* ── Summary ── */
        .sum-wrap { display: table; width: 100%; margin-bottom: 14px; border: 1px solid #e2e8f0; border-radius: 7px; overflow: hidden; }
        .sum-cell { display: table-cell; width: 25%; padding: 9px 12px; background: #f8fafc; border-right: 1px solid #e2e8f0; vertical-align: middle; }
        .sum-cell:last-child { border-right: none; }
        .sum-val { font-size: 15px; font-weight: 900; color: #0f172a; line-height: 1; }
        .sum-val.blue  { color: #2563eb; }
        .sum-val.green { color: #16a34a; }
        .sum-val.amber { color: #d97706; }
        .sum-val.red   { color: #e11d48; }
        .sum-val.sm    { font-size: 11px; }
        .sum-lbl { font-size: 7px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

        /* ── Section label ── */
        .sec-lbl { font-size: 8px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px; padding-left: 6px; border-left: 2.5px solid #2563eb; }

        /* ── Table ── */
        table.main { width: 100%; border-collapse: collapse; }
        table.main thead tr { background: #1e40af; }
        table.main thead th { padding: 7px 6px; text-align: center; font-size: 7.5px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: .04em; border: none; }
        table.main thead th:first-child { border-radius: 4px 0 0 0; }
        table.main thead th:last-child  { border-radius: 0 4px 0 0; }
        table.main tbody tr:nth-child(even) { background: #f8fafc; }
        table.main tbody tr:nth-child(odd)  { background: #fff; }
        table.main tbody td { padding: 5.5px 6px; font-size: 9px; color: #374151; border: none; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        table.main tbody td.mid   { text-align: center; }
        table.main tbody td.right { text-align: right; }

        /* ── Cell elements ── */
        .av { display: inline-block; width: 18px; height: 18px; background: #2563eb; border-radius: 4px; text-align: center; line-height: 18px; font-size: 8px; font-weight: 700; color: #fff; vertical-align: middle; margin-right: 4px; }
        .nik { font-family: 'Courier New', monospace; font-size: 7.5px; background: #f1f5f9; padding: 1px 4px; border-radius: 3px; color: #475569; }
        .rt-b { display: inline-block; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; font-size: 7.5px; font-weight: 700; padding: 1px 5px; border-radius: 3px; }
        .rp { font-weight: 700; color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 5px; border-radius: 3px; display: inline-block; white-space: nowrap; font-size: 8.5px; }

        /* Prob bar */
        .prob-bg { display: inline-block; width: 28px; height: 3px; background: #e2e8f0; border-radius: 99px; vertical-align: middle; margin-right: 3px; overflow: hidden; }

        /* ── Footer ── */
        .foot { display: table; width: 100%; margin-top: 18px; }
        .foot-l { display: table-cell; vertical-align: bottom; font-size: 7px; color: #94a3b8; }
        .foot-l strong { color: #475569; }
        .foot-r { display: table-cell; vertical-align: bottom; text-align: right; width: 160px; }
        .ttd-box { border: 1px solid #e2e8f0; border-radius: 5px; padding: 7px 10px; text-align: center; background: #f8fafc; }
        .ttd-line { width: 90px; border-bottom: 1px solid #334155; margin: 22px auto 3px; }
        .ttd-name { font-size: 8px; font-weight: 700; color: #1e293b; }
        .ttd-pos  { font-size: 7px; color: #64748b; margin-top: 1px; }
        .ttd-lbl  { font-size: 7px; color: #94a3b8; }
    </style>
</head>
<body>

@php
    $totalPenerima = $laporans->count();
    $totalDana     = $laporans->sum('jumlah_bantuan') ?: ($totalPenerima * 300000);
    $avgProb = $laporans->avg(function ($item) {
        $p = $item->probability ?? 0;
        return $p <= 1 ? $p * 100 : $p;
    });
    $periodeAktif = $laporans->pluck('periode_bantuan')->filter()->unique()->count();
@endphp

{{-- HEADER --}}
<div class="hd">
    <div class="hd-logo-cell"><div class="hd-logo">S</div></div>
    <div class="hd-text-cell">
        <div class="hd-title">Laporan Penerima BLT-DD</div>
        <div class="hd-sub">Desa Ngerong, Kec. Gempol, Kab. Pasuruan &mdash; Penerima final BLT Dana Desa</div>
    </div>
    <div class="hd-meta-cell">
        <div class="hd-badge">Laporan Resmi</div>
        <div class="hd-date">Dicetak: {{ now()->translatedFormat('d F Y') }}</div>
    </div>
</div>

{{-- SUMMARY --}}
<div class="sum-wrap">
    <div class="sum-cell">
        <div class="sum-val blue">{{ $totalPenerima }}</div>
        <div class="sum-lbl">Total Penerima</div>
    </div>
    <div class="sum-cell">
        <div class="sum-val green sm">Rp {{ number_format($totalDana, 0, ',', '.') }}</div>
        <div class="sum-lbl">Total Dana</div>
    </div>
    <div class="sum-cell">
        <div class="sum-val amber">{{ number_format($avgProb, 1) }}%</div>
        <div class="sum-lbl">Rata-rata Probabilitas</div>
    </div>
    <div class="sum-cell">
        <div class="sum-val red">{{ $periodeAktif }}</div>
        <div class="sum-lbl">Periode Aktif</div>
    </div>
</div>

{{-- LABEL --}}
<div class="sec-lbl">Daftar Penerima Final</div>

{{-- TABLE --}}
<table class="main">
    <thead>
        <tr>
            <th style="width:20px;">#</th>
            <th style="text-align:left;">Nama</th>
            <th>NIK</th>
            <th>No KK</th>
            <th>RT</th>
            <th>Dusun</th>
            <th>Probabilitas</th>
            <th>Tgl Penetapan</th>
            <th>Periode</th>
            <th>Jumlah Bantuan</th>
        </tr>
    </thead>
    <tbody>
    @forelse($laporans as $index => $item)
        @php
            $prob = $item->probability ?? 0;
            if ($prob <= 1) $prob = $prob * 100;
            $nama = $item->nama_lengkap ?? '-';
            $init = strtoupper(substr($nama, 0, 1));
            $sc   = $prob >= 70 ? '#10b981' : ($prob >= 40 ? '#f59e0b' : '#f43f5e');
            $jml  = $item->jumlah_bantuan ?? 300000;
        @endphp
        <tr>
            <td class="mid" style="font-size:8px;color:#94a3b8;font-weight:700;">{{ $index + 1 }}</td>
            <td><span class="av">{{ $init }}</span>{{ $nama }}</td>
            <td class="mid"><span class="nik">{{ $item->nik ?? '-' }}</span></td>
            <td class="mid"><span class="nik">{{ $item->no_kk ?? '-' }}</span></td>
            <td class="mid"><span class="rt-b">RT {{ str_pad($item->nomor_rt ?? '0', 3, '0', STR_PAD_LEFT) }}</span></td>
            <td style="white-space:nowrap;">{{ $item->nama_dusun ?? '-' }}</td>
            <td class="mid">
                <span class="prob-bg">
                    <span style="display:inline-block;width:{{ min($prob,100) }}%;height:3px;background:{{ $sc }};border-radius:99px;vertical-align:top;"></span>
                </span>
                <span style="font-size:8.5px;font-weight:700;color:{{ $sc }};">{{ number_format($prob, 1) }}%</span>
            </td>
            <td class="mid" style="white-space:nowrap;color:#64748b;">{{ $item->tanggal_penetapan?->format('d-m-Y') ?? '-' }}</td>
            <td class="mid" style="white-space:nowrap;">{{ $item->periode_bantuan ?? '-' }}</td>
            <td class="right"><span class="rp">Rp {{ number_format($jml, 0, ',', '.') }}</span></td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="mid" style="padding:28px;color:#94a3b8;font-weight:600;">Belum ada data penerima final.</td>
        </tr>
    @endforelse
    </tbody>
</table>

{{-- FOOTER --}}
<div class="foot">
    <div class="foot-l">
        <div style="margin-bottom:2px;"><strong>SiBantuDes</strong> &mdash; Sistem Informasi Bantuan Langsung Tunai Dana Desa</div>
        <div>Digenerate otomatis pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        <div style="margin-top:5px;color:#cbd5e1;">{{ $totalPenerima }} penerima &bull; Total Rp {{ number_format($totalDana, 0, ',', '.') }}</div>
    </div>
    <div class="foot-r">
        <div class="ttd-box">
            <div class="ttd-lbl">Mengetahui,</div>
            <div class="ttd-line"></div>
            <div class="ttd-name">Kepala Desa Ngerong</div>
            <div class="ttd-pos">Kec. Gempol, Kab. Pasuruan</div>
        </div>
    </div>
</div>

</body>
</html>