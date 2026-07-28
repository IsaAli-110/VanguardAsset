@extends('layouts.app')

@section('styles')
<style>
    .page-header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .page-header h1 { font-size: 1.9rem; font-weight: 800; }
    .page-header p { color: var(--text-muted); margin-top: 0.35rem; }

    .maintenance-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .maint-stat {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: var(--transition-smooth);
    }
    .maint-stat:hover {
        transform: translateY(-3px);
        border-color: var(--border-glow);
        box-shadow: 0 8px 25px -8px rgba(0,0,0,0.4);
    }

    .maint-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;
    }
    .maint-icon.danger { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    .maint-icon.warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .maint-icon.success { background: rgba(16, 185, 129, 0.15); color: #34d399; }

    .maint-info .label { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .maint-info .val { font-size: 1.8rem; font-weight: 800; line-height: 1.1; }
    .maint-info .val.danger { color: #f87171; }
    .maint-info .val.warning { color: #fbbf24; }
    .maint-info .val.success { color: #34d399; }

    .section-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem;
        display: flex; align-items: center; gap: 0.6rem;
    }
    .section-title i { color: var(--primary); }

    .asset-row {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem; border-radius: var(--radius-md);
        border: 1px solid var(--border-dim);
        margin-bottom: 0.75rem;
        transition: var(--transition-smooth);
    }
    .asset-row:hover {
        border-color: var(--border-glow);
        background: rgba(255,255,255,0.02);
    }

    .asset-icon-box {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .asset-icon-box.overdue { background: rgba(239, 68, 68, 0.12); color: #f87171; }
    .asset-icon-box.upcoming { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }

    .asset-meta { flex: 1; }
    .asset-meta .name { font-weight: 600; font-size: 0.95rem; }
    .asset-meta .name a { color: #818cf8; text-decoration: none; }
    .asset-meta .name a:hover { text-decoration: underline; }
    .asset-meta .sub { font-size: 0.82rem; color: var(--text-muted); margin-top: 0.15rem; }

    .days-badge {
        padding: 0.3rem 0.75rem; border-radius: 20px;
        font-size: 0.8rem; font-weight: 700; white-space: nowrap;
    }
    .days-badge.overdue { background: rgba(239, 68, 68, 0.12); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.25); }
    .days-badge.upcoming { background: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.25); }

    .btn-mark {
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #34d399; padding: 0.35rem 0.8rem;
        border-radius: var(--radius-md); cursor: pointer;
        font-weight: 600; font-size: 0.82rem;
        transition: var(--transition-smooth);
        display: inline-flex; align-items: center; gap: 0.35rem;
    }
    .btn-mark:hover {
        background: rgba(16, 185, 129, 0.25);
        border-color: rgba(16, 185, 129, 0.5);
    }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3; display: block; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fa-solid fa-screwdriver-wrench" style="color: #fbbf24;"></i> Jadwal Maintenance Aset</h1>
        <p>Pantau dan kelola jadwal perawatan aset fisik secara berkala</p>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
</div>

{{-- Stats Ringkasan --}}
<div class="maintenance-stats">
    <div class="maint-stat">
        <div class="maint-icon danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="maint-info">
            <div class="label">Terlambat</div>
            <div class="val danger">{{ $dueAssets->count() }}</div>
        </div>
    </div>
    <div class="maint-stat">
        <div class="maint-icon warning"><i class="fa-solid fa-clock"></i></div>
        <div class="maint-info">
            <div class="label">Mendatang (14 hari)</div>
            <div class="val warning">{{ $upcomingAssets->count() }}</div>
        </div>
    </div>
    <div class="maint-stat">
        <div class="maint-icon success"><i class="fa-solid fa-circle-check"></i></div>
        <div class="maint-info">
            <div class="label">Total Aset Fisik</div>
            <div class="val success">{{ $totalPhysical }}</div>
        </div>
    </div>
</div>

{{-- Aset Terlambat Maintenance --}}
<div class="section-card">
    <div class="section-title">
        <i class="fa-solid fa-triangle-exclamation" style="color: #f87171;"></i>
        Terlambat Maintenance ({{ $dueAssets->count() }})
    </div>

    @if($dueAssets->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-circle-check"></i>
            <p>Semua aset fisik sudah terawat. Tidak ada yang terlambat!</p>
        </div>
    @else
        @foreach($dueAssets as $asset)
            <div class="asset-row">
                <div class="asset-icon-box overdue"><i class="fa-solid fa-server"></i></div>
                <div class="asset-meta">
                    <div class="name"><a href="{{ route('assets.show', $asset->id) }}">{{ $asset->name }}</a></div>
                    <div class="sub">
                        {{ $asset->id }} &bull; Interval: {{ $asset->detail_json['maintenance_interval'] ?? '?' }} hari
                        &bull; Terakhir: {{ $asset->last_maintenance_date ? \Carbon\Carbon::parse($asset->last_maintenance_date)->format('d M Y') : 'Belum pernah' }}
                    </div>
                </div>
                <span class="days-badge overdue">
                    Terlambat {{ abs(\App\Services\MaintenanceSchedulerService::daysUntilMaintenance($asset)) }} hari
                </span>
                @if(Auth::user()->isAdmin())
                <form action="{{ route('assets.mark-maintained', $asset->id) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-mark"><i class="fa-solid fa-check"></i> Tandai Selesai</button>
                </form>
                @endif
            </div>
        @endforeach
    @endif
</div>

{{-- Aset Mendekati Jadwal Maintenance --}}
<div class="section-card">
    <div class="section-title">
        <i class="fa-solid fa-clock" style="color: #fbbf24;"></i>
        Mendekati Jadwal Maintenance ({{ $upcomingAssets->count() }})
    </div>

    @if($upcomingAssets->isEmpty())
        <div class="empty-state">
            <i class="fa-regular fa-calendar-check"></i>
            <p>Tidak ada aset yang mendekati jadwal maintenance dalam 14 hari ke depan.</p>
        </div>
    @else
        @foreach($upcomingAssets as $asset)
            <div class="asset-row">
                <div class="asset-icon-box upcoming"><i class="fa-solid fa-server"></i></div>
                <div class="asset-meta">
                    <div class="name"><a href="{{ route('assets.show', $asset->id) }}">{{ $asset->name }}</a></div>
                    <div class="sub">
                        {{ $asset->id }} &bull; Interval: {{ $asset->detail_json['maintenance_interval'] ?? '?' }} hari
                        &bull; Terakhir: {{ $asset->last_maintenance_date ? \Carbon\Carbon::parse($asset->last_maintenance_date)->format('d M Y') : 'Belum pernah' }}
                    </div>
                </div>
                <span class="days-badge upcoming">
                    {{ \App\Services\MaintenanceSchedulerService::daysUntilMaintenance($asset) }} hari lagi
                </span>
            </div>
        @endforeach
    @endif
</div>

@endsection
