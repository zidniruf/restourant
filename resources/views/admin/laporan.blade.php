<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking — {{ $bulanLabel }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:  #FAF6F0;
            --brown:  #1C1007;
            --gold:   #C88E5B;
            --muted:  #6B5444;
            --border: #E2D0BA;
            --white:  #ffffff;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Lato',sans-serif; background:var(--cream); color:var(--brown); font-size:13px; }
        .page { max-width:900px; margin:0 auto; padding:24px 16px 60px; }

        /* ── BUTTONS ── */
        .no-print { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px; }
        .btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:10px 20px; border:none; cursor:pointer;
            font-family:'Lato',sans-serif; font-size:12px;
            font-weight:700; letter-spacing:.08em; text-transform:uppercase;
            text-decoration:none; transition:opacity .2s;
        }
        .btn:hover { opacity:.8; }
        .btn-dark { background:var(--brown); color:var(--white); }
        .btn-light { background:var(--white); color:var(--brown); border:1px solid var(--border); }

        /* ── HEADER ── */
        .header { border-top:3px solid var(--gold); padding-top:20px; margin-bottom:24px; }
        .header-row { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; }
        .header-title { font-family:'Playfair Display',serif; font-size:clamp(22px,5vw,30px); font-weight:700; line-height:1.1; }
        .header-period {
            display:inline-block; margin-top:8px;
            background:var(--gold); color:var(--white);
            font-size:11px; font-weight:700; letter-spacing:.1em;
            text-transform:uppercase; padding:4px 14px;
        }
        .header-meta { text-align:right; font-size:11px; color:var(--muted); font-weight:300; line-height:1.8; }

        /* ── STATS ── */
        .stats { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:24px; }
        .stat {
            background:var(--white); border:1px solid var(--border);
            padding:14px 12px; text-align:center; position:relative; overflow:hidden;
        }
        .stat::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background:var(--gold); }
        .stat-num { font-family:'Playfair Display',serif; font-size:clamp(18px,3vw,26px); font-weight:700; line-height:1; }
        .stat-lbl { font-size:9px; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); margin-top:6px; font-weight:700; }

        /* ── BADGES ── */
        .badge { display:inline-block; padding:2px 9px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; }
        .b-orange { background:#fff7ed; color:#c2410c; }
        .b-blue   { background:#eff6ff; color:#1d4ed8; }
        .b-purple { background:#f5f3ff; color:#7c3aed; }
        .b-green  { background:#dcfce7; color:#15803d; }
        .b-red    { background:#fef2f2; color:#dc2626; }
        .b-gray   { background:#f3f4f6; color:#6b7280; }

        /* ── DESKTOP TABLE ── */
        .tbl-wrap { overflow-x:auto; margin-bottom:28px; }
        table { width:100%; border-collapse:collapse; background:var(--white); border:1px solid var(--border); }
        thead tr { background:var(--brown); }
        thead th {
            padding:9px 10px; text-align:left;
            font-size:9px; text-transform:uppercase;
            letter-spacing:.08em; font-weight:700;
            color:#E8C99A; white-space:nowrap;
        }
        tbody tr { border-bottom:1px solid var(--border); }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:#FDF9F5; }
        tbody td { padding:9px 10px; vertical-align:top; font-size:12px; }
        td.muted { color:var(--muted); font-size:11px; }
        td.bold  { font-weight:700; }
        .no-data td { text-align:center; padding:40px; color:var(--muted); font-style:italic; }

        /* ── MOBILE CARDS ── */
        .cards { display:none; margin-bottom:28px; }
        .card { background:var(--white); border:1px solid var(--border); margin-bottom:12px; overflow:hidden; }
        .card-top {
            background:var(--brown); color:var(--white);
            padding:12px 14px; display:flex;
            justify-content:space-between; align-items:center;
            gap:8px; flex-wrap:wrap;
        }
        .card-name { font-family:'Playfair Display',serif; font-size:15px; font-weight:600; }
        .card-no { font-size:10px; opacity:.6; margin-top:1px; }
        .card-body { padding:14px; }
        .card-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px 14px; margin-bottom:0; }
        .field-lbl { font-size:9px; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); font-weight:700; margin-bottom:2px; }
        .field-val { font-size:12px; color:var(--brown); font-weight:600; }
        .card-section { border-top:1px dashed var(--border); padding-top:10px; margin-top:12px; font-size:12px; color:var(--muted); }
        .card-section strong { color:var(--brown); }
        .card-foot {
            background:#FDF9F5; border-top:1px solid var(--border);
            padding:10px 14px; display:flex;
            justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;
        }
        .card-total-lbl { font-size:9px; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
        .card-total { font-family:'Playfair Display',serif; font-size:17px; font-weight:700; }

        /* ── FOOTER ── */
        .footer {
            border-top:1px solid var(--border); padding-top:14px;
            display:flex; justify-content:space-between; align-items:center;
            flex-wrap:wrap; gap:8px; font-size:11px; color:var(--muted);
        }
        .footer-brand { font-family:'Playfair Display',serif; font-size:13px; color:var(--brown); font-weight:600; }

        /* ── RESPONSIVE ── */
        @media (max-width:640px) {
            .tbl-wrap { display:none; }
            .cards { display:block; }
            .stats { grid-template-columns:1fr 1fr; }
            .stats .stat:nth-child(3),.stats .stat:nth-child(4) { grid-column:span 1; }
            .header-meta { text-align:left; }
        }
        @media (max-width:360px) {
            .stats { grid-template-columns:1fr; }
        }

        /* ── PRINT ── */
        @media print {
            .no-print { display:none !important; }
            body { background:#fff; }
            .page { padding:0; max-width:100%; }
            .cards { display:none !important; }
            .tbl-wrap { display:block !important; }
        }
    </style>
</head>
<body>
<div class="page">

    <div class="no-print">
        <button class="btn btn-dark" onclick="window.print()">🖨 Cetak / PDF</button>
        <a href="/admin/bookings" class="btn btn-light">← Kembali</a>
    </div>

    {{-- Header --}}
    <div class="header">
        <div class="header-row">
            <div>
                <div class="header-title">Laporan Booking</div>
                <div class="header-period">{{ $bulanLabel }}</div>
            </div>
            <div class="header-meta">
                {{ config('app.name') }}<br>
                Dicetak {{ now()->format('d M Y') }}<br>
                {{ now()->format('H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-num">{{ $totalBooking }}</div>
            <div class="stat-lbl">Total Booking</div>
        </div>
        <div class="stat">
            <div class="stat-num">{{ $bookings->where('status','approved')->count() + $bookings->where('status','confirmed')->count() }}</div>
            <div class="stat-lbl">Disetujui</div>
        </div>
        <div class="stat">
            <div class="stat-num">{{ $totalSelesai }}</div>
            <div class="stat-lbl">Selesai</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="font-size:clamp(13px,2.5vw,18px)">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-lbl">Total Omset</div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>WhatsApp</th>
                    <th>Tanggal</th>
                    <th>Tempat</th>
                    <th>Sesi</th>
                    <th>Jam Acara</th>
                    <th>Makan Siap</th>
                    <th>Tamu</th>
                    <th>Menu</th>
                    <th>Catatan</th>
                    <th>Total</th>
                    <th>Check-in</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($bookings as $i => $b)
            @php
                $menuStr = $b->items->map(fn($it) => ($it->item->title ?? '-') . ' x' . $it->qty)->implode(', ');
                [$cls,$lbl] = match($b->status) {
                    'pending'   => ['b-orange','Pending'],
                    'approved'  => ['b-blue','Disetujui'],
                    'confirmed' => ['b-purple','Dikonfirmasi'],
                    'completed' => ['b-green','Selesai'],
                    'rejected'  => ['b-red','Ditolak'],
                    'cancelled' => ['b-gray','Dibatalkan'],
                    default     => ['b-gray', $b->status],
                };
            @endphp
            <tr>
                <td class="muted">{{ $i+1 }}</td>
                <td class="bold">{{ $b->name }}</td>
                <td class="muted">{{ $b->phone }}</td>
                <td style="white-space:nowrap">{{ $b->booking_date->format('d M Y') }}</td>
                <td>{{ optional($b->table)->name ?? '-' }}</td>
                <td style="white-space:nowrap">
                    @if($b->session)
                        {{ $b->session->name }}<br>
                        <span style="font-size:10px;color:var(--muted)">{{ substr($b->session->start_time,0,5) }}–{{ substr($b->session->end_time,0,5) }}</span>
                    @else -
                    @endif
                </td>
                <td>{{ $b->event_time ? substr($b->event_time,0,5) : '-' }}</td>
                <td>{{ $b->food_ready_time ? substr($b->food_ready_time,0,5) : '-' }}</td>
                <td style="text-align:center">{{ $b->guest_count }}</td>
                <td class="muted" style="max-width:140px;font-size:11px">{{ $menuStr ?: '-' }}</td>
                <td class="muted" style="max-width:120px;font-size:11px;font-style:italic">{{ $b->note ?: '-' }}</td>
                <td class="bold" style="white-space:nowrap">Rp {{ number_format($b->total_price,0,',','.') }}</td>
                <td style="font-size:11px;white-space:nowrap">
                    @if($b->checked_in_at)
                        <span style="color:#15803d;font-weight:700">✅ {{ $b->checked_in_at->format('d M, H:i') }}</span>
                    @else
                        <span class="muted">-</span>
                    @endif
                </td>
                <td><span class="badge {{ $cls }}">{{ $lbl }}</span></td>
            </tr>
            @empty
            <tr class="no-data"><td colspan="14">Tidak ada data booking pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="cards">
        @forelse($bookings as $i => $b)
        @php
            $menuStr = $b->items->map(fn($it) => ($it->item->title ?? '-') . ' x' . $it->qty)->implode(', ');
            [$cls,$lbl] = match($b->status) {
                'pending'   => ['b-orange','Pending'],
                'approved'  => ['b-blue','Disetujui'],
                'confirmed' => ['b-purple','Dikonfirmasi'],
                'completed' => ['b-green','Selesai'],
                'rejected'  => ['b-red','Ditolak'],
                'cancelled' => ['b-gray','Dibatalkan'],
                default     => ['b-gray', $b->status],
            };
        @endphp
        <div class="card">
            <div class="card-top">
                <div>
                    <div class="card-name">{{ $b->name }}</div>
                    <div class="card-no">📱 {{ $b->phone }}</div>
                </div>
                <span class="badge {{ $cls }}">{{ $lbl }}</span>
            </div>
            <div class="card-body">
                <div class="card-grid">
                    <div>
                        <div class="field-lbl">Tanggal</div>
                        <div class="field-val">{{ $b->booking_date->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="field-lbl">Tempat</div>
                        <div class="field-val">{{ optional($b->table)->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="field-lbl">Sesi</div>
                        <div class="field-val">
                            @if($b->session)
                                {{ $b->session->name }}
                                <span style="font-size:10px;color:var(--muted);font-weight:400">({{ substr($b->session->start_time,0,5) }}–{{ substr($b->session->end_time,0,5) }})</span>
                            @else - @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-lbl">Jumlah Tamu</div>
                        <div class="field-val">{{ $b->guest_count }} orang</div>
                    </div>
                    <div>
                        <div class="field-lbl">Jam Acara</div>
                        <div class="field-val">{{ $b->event_time ? substr($b->event_time,0,5) : '-' }}</div>
                    </div>
                    <div>
                        <div class="field-lbl">Makan Siap</div>
                        <div class="field-val">{{ $b->food_ready_time ? substr($b->food_ready_time,0,5) : '-' }}</div>
                    </div>
                    <div>
                        <div class="field-lbl">Check-in</div>
                        <div class="field-val" style="font-size:11px">
                            @if($b->checked_in_at)
                                <span style="color:#15803d">✅ {{ $b->checked_in_at->format('d M, H:i') }}</span>
                            @else - @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-lbl">Total</div>
                        <div class="field-val">Rp {{ number_format($b->total_price,0,',','.') }}</div>
                    </div>
                </div>

                @if($menuStr)
                <div class="card-section">
                    <strong>🛒 Menu:</strong> {{ $menuStr }}
                </div>
                @endif

                @if($b->note)
                <div class="card-section" style="font-style:italic">
                    <strong>📝 Catatan:</strong> {{ $b->note }}
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:var(--muted);font-style:italic">
            Tidak ada data booking pada periode ini.
        </div>
        @endforelse
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>
            <div class="footer-brand">{{ config('app.name') }}</div>
            <div>{{ $totalBooking }} booking · {{ $totalSelesai }} selesai · {{ $bookings->where('status','approved')->count() + $bookings->where('status','confirmed')->count() }} disetujui</div>
        </div>
        <div style="text-align:right">
            Laporan {{ $bulanLabel }}<br>
            Dicetak {{ now()->format('d M Y, H:i') }} WIB
        </div>
    </div>

</div>
</body>
</html>