@extends('layouts.app')

@section('styles')
<style>
    .page-header { margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.9rem; font-weight: 800; }
    .page-header p { color: var(--text-muted); margin-top: 0.35rem; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        border-color: var(--border-glow);
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.05;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-icon.indigo { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
    .stat-icon.emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; }
    .stat-icon.amber { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .stat-icon.rose { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    .stat-icon.sky { background: rgba(14, 165, 233, 0.15); color: #38bdf8; }
    .stat-icon.violet { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
    .stat-icon.pink { background: rgba(236, 72, 153, 0.15); color: #f472b6; }

    .stat-info .label { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
    .stat-info .value { font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-info .value.indigo { color: #818cf8; }
    .stat-info .value.emerald { color: #34d399; }
    .stat-info .value.amber { color: #fbbf24; }
    .stat-info .value.rose { color: #f87171; }
    .stat-info .value.sky { color: #38bdf8; }
    .stat-info .value.violet { color: #a78bfa; }
    .stat-info .value.pink { color: #f472b6; }

    /* Currency Value (compact Rp display) */
    .currency-value {
        display: flex; align-items: baseline; gap: 0.3rem;
        line-height: 1;
    }
    .currency-prefix {
        font-size: 0.85rem; font-weight: 600; opacity: 0.6;
    }
    .currency-num {
        font-size: 1.5rem; font-weight: 800;
    }
    .currency-value.violet .currency-prefix, .currency-value.violet .currency-num { color: #a78bfa; }
    .currency-value.pink .currency-prefix, .currency-value.pink .currency-num { color: #f472b6; }

    /* Sparkline */
    .sparkline-container {
        position: absolute; bottom: 0; right: 0; left: 0;
        height: 40px; opacity: 0.15; pointer-events: none;
    }
    .sparkline-container svg { width: 100%; height: 100%; }

    /* Progress Ring */
    .progress-ring-wrapper {
        display: flex; align-items: center; gap: 1.5rem;
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: var(--transition-smooth);
    }
    .progress-ring-wrapper:hover {
        border-color: var(--border-glow);
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }
    .progress-ring { position: relative; width: 120px; height: 120px; flex-shrink: 0; }
    .progress-ring svg { transform: rotate(-90deg); }
    .progress-ring .ring-bg { fill: none; stroke: rgba(255,255,255,0.05); stroke-width: 8; }
    .progress-ring .ring-fill {
        fill: none; stroke-width: 8; stroke-linecap: round;
        transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .progress-ring .ring-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        font-size: 1.6rem; font-weight: 800;
    }
    .ring-stats { display: flex; flex-direction: column; gap: 0.5rem; }
    .ring-stats .ring-label { font-size: 0.85rem; color: var(--text-muted); }
    .ring-stats .ring-value { font-weight: 700; font-size: 0.95rem; }

    .section-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; }
    .section-title i { color: var(--primary); }

    .quick-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }

    .action-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-decoration: none;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 200px;
        transition: var(--transition-smooth);
    }
    .action-card:hover { border-color: var(--border-glow); box-shadow: 0 8px 25px -8px rgba(99,102,241,0.3); }
    .action-card i { font-size: 1.5rem; }
    .action-card .action-label { font-weight: 600; font-size: 0.95rem; }
    .action-card .action-desc { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem; }

    .recent-requests-table td { vertical-align: middle; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3; display: block; }

    .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 768px) { .dashboard-grid { grid-template-columns: 1fr; } }

    .top-assets-table td, .top-assets-table th { padding: 0.75rem 1rem; }
    .top-assets-table .rank {
        width: 28px; height: 28px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem;
    }
    .rank-1 { background: rgba(250, 204, 21, 0.15); color: #facc15; }
    .rank-2 { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }
    .rank-3 { background: rgba(180, 83, 9, 0.15); color: #d97706; }
    .rank-other { background: rgba(255,255,255,0.05); color: var(--text-muted); }
</style>
@endsection

@section('content')

<div class="page-header">
    <h1>👋 Selamat Datang, {{ $user->name }}</h1>
    <p>Berikut ringkasan sistem VanguardAsset Anda — {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    @if($user->isAdmin() || $user->isManager())
    <div class="stat-card" data-tilt>
        <div class="stat-icon indigo"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="stat-info"><div class="label">Total Aset</div><div class="value indigo" data-count="{{ $totalAssets }}">0</div></div>
        @include('partials.sparkline', ['data' => $sparklineData, 'color' => '#818cf8'])
    </div>
    @endif
    <div class="stat-card" data-tilt>
        <div class="stat-icon emerald"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info"><div class="label">Tersedia</div><div class="value emerald" data-count="{{ $availableAssets }}">0</div></div>
    </div>
    <div class="stat-card" data-tilt>
        <div class="stat-icon amber"><i class="fa-solid fa-hand-holding"></i></div>
        <div class="stat-info"><div class="label">Dipinjam</div><div class="value amber" data-count="{{ $borrowedAssets }}">0</div></div>
    </div>
    <div class="stat-card" data-tilt>
        <div class="stat-icon rose"><i class="fa-solid fa-wrench"></i></div>
        <div class="stat-info"><div class="label">Dalam Perawatan</div><div class="value rose" data-count="{{ $maintenanceAssets }}">0</div></div>
    </div>
    @if($user->isAdmin() || $user->isManager())
    <div class="stat-card" data-tilt>
        <div class="stat-icon sky"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="stat-info"><div class="label">Menunggu Persetujuan</div><div class="value sky" data-count="{{ $pendingRequests }}">0</div></div>
    </div>
    <a href="{{ route('assets.maintenance') }}" style="text-decoration:none;color:inherit;">
    <div class="stat-card" data-tilt style="{{ $maintenanceDueCount > 0 ? 'border: 1px solid rgba(245,158,11,0.4);' : '' }}">
        <div class="stat-icon" style="{{ $maintenanceDueCount > 0 ? 'background:rgba(245,158,11,0.2);color:#fbbf24;' : 'background:rgba(16,185,129,0.15);color:#34d399;' }}"><i class="fa-solid fa-screwdriver-wrench"></i></div>
        <div class="stat-info"><div class="label">Maintenance Due</div><div class="value" data-count="{{ $maintenanceDueCount }}" style="{{ $maintenanceDueCount > 0 ? 'color:#fbbf24;' : 'color:#34d399;' }}">0</div></div>
    </div>
    </a>
    <div class="stat-card" data-tilt>
        <div class="stat-icon violet"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="stat-info">
            <div class="label">Total Nilai Aset</div>
            <div class="currency-value violet">
                <span class="currency-prefix">Rp</span>
                <span class="currency-num" data-count="{{ $totalAssetValue }}">0</span>
            </div>
        </div>
    </div>
    <div class="stat-card" data-tilt>
        <div class="stat-icon pink"><i class="fa-solid fa-arrow-trend-down"></i></div>
        <div class="stat-info">
            <div class="label">Total Depresiasi</div>
            <div class="currency-value pink">
                <span class="currency-prefix">Rp</span>
                <span class="currency-num" data-count="{{ $totalDepreciated }}">0</span>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Progress Ring: Ketersediaan Aset --}}
@if($user->isAdmin() || $user->isManager())
<div class="progress-ring-wrapper" data-tilt>
    <div class="progress-ring">
        <svg width="120" height="120" viewBox="0 0 120 120">
            <circle class="ring-bg" cx="60" cy="60" r="52"/>
            <circle class="ring-fill" cx="60" cy="60" r="52"
                stroke="url(#ringGrad)"
                stroke-dasharray="{{ 2 * pi() * 52 }}"
                stroke-dashoffset="{{ 2 * pi() * 52 }}"
                data-target-offset="{{ 2 * pi() * 52 * (1 - $availabilityPercent / 100) }}"/>
            <defs>
                <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#6366f1"/>
                    <stop offset="100%" stop-color="#c084fc"/>
                </linearGradient>
            </defs>
        </svg>
        <div class="ring-text" style="color:#818cf8;" data-count="{{ $availabilityPercent }}">0<span style="font-size:0.9rem;font-weight:600;">%</span></div>
    </div>
    <div class="ring-stats">
        <div style="font-size:1.1rem;font-weight:700;"><i class="fa-solid fa-chart-pie" style="color:var(--primary);margin-right:0.4rem;"></i> Ketersediaan Aset</div>
        <div class="ring-label">Dari total <strong style="color:var(--text-main);">{{ $totalAssets }}</strong> aset, <strong style="color:#34d399;">{{ $availableAssets }}</strong> tersedia saat ini</div>
        <div style="display:flex;gap:1rem;margin-top:0.25rem;">
            <div class="ring-value"><span style="color:#34d399;">●</span> Tersedia: {{ $availableAssets }}</div>
            <div class="ring-value"><span style="color:#fbbf24;">●</span> Dipinjam: {{ $borrowedAssets }}</div>
            <div class="ring-value"><span style="color:#f87171;">●</span> Maintenance: {{ $maintenanceAssets }}</div>
        </div>
    </div>
</div>
@endif

{{-- Overdue Borrowing Alert --}}
@if($overdueRequests->isNotEmpty() && ($user->isAdmin() || $user->isManager()))
<div style="background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.2); border-radius:var(--radius-lg); padding:1.25rem 1.5rem; margin-bottom:2rem; backdrop-filter:blur(16px);">
    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1rem;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#f87171; font-size:1.1rem;"></i>
        <span style="font-size:1rem; font-weight:700; color:#f87171;">Aset Overdue ({{ $overdueRequests->count() }})</span>
        <a href="{{ route('approvals.index') }}" style="margin-left:auto; font-size:0.8rem; color:var(--text-muted); text-decoration:none;">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    @foreach($overdueRequests as $overReq)
    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid rgba(255,255,255,0.04);">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;color:white;background:linear-gradient(135deg,#ef4444,#f87171);flex-shrink:0;">
                {{ strtoupper(substr($overReq->user->name ?? '?', 0, 1)) }}
            </div>
            <div>
                <div style="font-size:0.88rem; font-weight:600;">
                    <a href="{{ route('assets.show', $overReq->asset_id) }}" style="color:inherit; text-decoration:none;">{{ $overReq->asset->name ?? 'Unknown' }}</a>
                </div>
                <div style="font-size:0.75rem; color:var(--text-muted);">
                    {{ $overReq->user->name }} · Jatuh tempo: {{ $overReq->due_date->format('d M Y') }}
                </div>
            </div>
        </div>
        <span style="background:rgba(239,68,68,0.15); color:#f87171; padding:0.25rem 0.6rem; border-radius:20px; font-size:0.75rem; font-weight:700;">
            {{ $overReq->overdueDays() }} hari terlambat
        </span>
    </div>
    @endforeach
</div>
@endif
<div class="section-title"><i class="fa-solid fa-bolt"></i> Aksi Cepat</div>
<div class="quick-actions">
    <a href="{{ route('assets.index') }}" class="action-card" data-tilt>
        <i class="fa-solid fa-boxes-stacked" style="color:#818cf8;"></i>
        <div><div class="action-label">Katalog Aset</div><div class="action-desc">Lihat seluruh daftar aset</div></div>
    </a>
    @if($user->isAdmin())
    <a href="{{ route('assets.create') }}" class="action-card" data-tilt>
        <i class="fa-solid fa-plus-circle" style="color:#34d399;"></i>
        <div><div class="action-label">Tambah Aset Baru</div><div class="action-desc">Daftarkan aset fisik atau digital</div></div>
    </a>
    @endif
    @if($user->isManager() || $user->isAdmin())
    <a href="{{ route('approvals.index') }}" class="action-card" data-tilt>
        <i class="fa-solid fa-list-check" style="color:#fbbf24;"></i>
        <div><div class="action-label">Tinjau Permohonan</div><div class="action-desc">{{ $pendingRequests }} menunggu persetujuan</div></div>
    </a>
    @endif
    @if($user->isStaff())
    <a href="{{ route('approvals.index') }}" class="action-card" data-tilt>
        <i class="fa-solid fa-ticket" style="color:#38bdf8;"></i>
        <div><div class="action-label">Permohonan Saya</div><div class="action-desc">Lacak riwayat peminjaman Anda</div></div>
    </a>
    @endif
</div>

{{-- Top 5 Aset Termahal --}}
@if(($user->isAdmin() || $user->isManager()) && $topExpensiveAssets->isNotEmpty())
<div class="card" style="padding: 1.75rem;" data-tilt>
    <div class="section-title" style="margin-bottom: 1rem;">
        <i class="fa-solid fa-gem"></i> Top 5 Aset Bernilai Tertinggi
    </div>
    <div class="table-responsive top-assets-table">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nama Aset</th>
                    <th>Tipe</th>
                    <th>Tgl Beli</th>
                    <th style="text-align:right;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topExpensiveAssets as $i => $a)
                <tr>
                    <td><span class="rank {{ $i < 3 ? 'rank-'.($i+1) : 'rank-other' }}">{{ $i+1 }}</span></td>
                    <td><a href="{{ route('assets.show', $a->id) }}" style="color:#818cf8;text-decoration:none;font-weight:600;">{{ $a->name }}</a><br><span style="font-size:0.78rem;color:var(--text-muted);">{{ $a->id }}</span></td>
                    <td><span class="badge badge-info"><i class="fa-solid fa-{{ $a->type === 'physical' ? 'server' : 'cloud' }}"></i> {{ ucfirst($a->type) }}</span></td>
                    <td style="color:var(--text-muted);font-size:0.9rem;">{{ $a->purchase_date ? $a->purchase_date->format('d M Y') : '—' }}</td>
                    <td style="text-align:right;font-weight:700;color:#a78bfa;">Rp {{ number_format($a->purchase_cost, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Dashboard Charts --}}
<div class="dashboard-grid" style="margin-bottom: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <div class="section-title"><i class="fa-solid fa-chart-pie"></i> Distribusi Tipe Aset</div>
        <div style="height: 250px; position: relative; display: flex; justify-content: center;">
            <canvas id="typeChart"></canvas>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <div class="section-title"><i class="fa-solid fa-chart-bar"></i> Status Ketersediaan Aset</div>
        <div style="height: 250px; position: relative;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

{{-- Recent Activity Table --}}
<div class="card" style="padding: 1.75rem;">
    <div class="section-title" style="margin-bottom: 1rem;">
        <i class="fa-solid fa-clock-rotate-left"></i>
        @if($user->isAdmin() || $user->isManager())
            Permohonan Menunggu Persetujuan
        @else
            Permohonan Terbaru Saya
        @endif
    </div>

    @if($recentRequests->isEmpty())
        <div class="empty-state">
            <i class="fa-regular fa-folder-open"></i>
            <p>Belum ada permohonan saat ini.</p>
        </div>
    @else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    @if($user->isAdmin() || $user->isManager())
                    <th>Pemohon</th>
                    @endif
                    <th>Aset</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Dikirim</th>
                    @if($user->isAdmin() || $user->isManager())
                    <th>Tindakan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($recentRequests as $req)
                <tr>
                    @if($user->isAdmin() || $user->isManager())
                    <td>
                        <div style="font-weight: 600;">{{ $req->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $req->user->email }}</div>
                    </td>
                    @endif
                    <td>
                        <a href="{{ route('assets.show', $req->asset_id) }}" style="color: #818cf8; text-decoration: none; font-weight: 600;">{{ $req->asset->name ?? $req->asset_id }}</a>
                    </td>
                    <td>{{ $req->duration }} hari</td>
                    <td>
                        @if($req->status === 'Pending')
                            <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Menunggu</span>
                        @elseif($req->status === 'Approved')
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> Disetujui</span>
                        @else
                            <span class="badge badge-danger"><i class="fa-solid fa-times"></i> Ditolak</span>
                        @endif
                    </td>
                    <td style="color: var(--text-muted); font-size: 0.9rem;">{{ $req->created_at->diffForHumans() }}</td>
                    @if(($user->isAdmin() || $user->isManager()) && $req->status === 'Pending')
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <form action="{{ route('approvals.approve', $req->id) }}" method="POST" style="margin:0;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-primary" style="padding: 0.3rem 0.75rem; font-size: 0.8rem;"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <form action="{{ route('approvals.reject', $req->id) }}" method="POST" style="margin:0;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.75rem; font-size: 0.8rem;"><i class="fa-solid fa-times"></i></button>
                            </form>
                        </div>
                    </td>
                    @elseif($user->isAdmin() || $user->isManager())
                    <td style="color: var(--text-muted); font-size: 0.85rem;">—</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Animate Progress Ring
        document.querySelectorAll('.ring-fill').forEach(ring => {
            const targetOffset = parseFloat(ring.getAttribute('data-target-offset'));
            setTimeout(() => {
                ring.style.strokeDashoffset = targetOffset;
            }, 300);
        });

        // Type Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aset Fisik', 'Aset Digital'],
                datasets: [{
                    data: [{{ $physicalCount }}, {{ $digitalCount }}],
                    backgroundColor: ['#6366f1', '#ec4899'],
                    borderColor: 'rgba(15, 23, 42, 0.8)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: { family: "'Inter', sans-serif", size: 11 }
                        }
                    }
                }
            }
        });

        // Status Chart — pakai variabel dari controller
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Tersedia', 'Dipinjam', 'Dalam Perawatan'],
                datasets: [{
                    data: [{{ $availableAssets }}, {{ $borrowedAssets }}, {{ $maintenanceAssets }}],
                    backgroundColor: ['#10b981', '#fbbf24', '#ef4444'],
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: "'Inter', sans-serif", size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', precision: 0 }
                    }
                }
            }
        });
    });
</script>
@endsection
