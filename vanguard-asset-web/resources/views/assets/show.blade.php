@extends('layouts.app')

@section('styles')
<style>
    .back-btn { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: var(--transition-smooth); margin-bottom: 1.5rem; }
    .back-btn:hover { color: var(--text-main); }

    .asset-detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    @media(max-width: 900px) { .asset-detail-grid { grid-template-columns: 1fr; } }

    .asset-hero {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .asset-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    .asset-hero.physical::before { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .asset-hero.digital::before  { background: linear-gradient(135deg, #ec4899, #d946ef); }

    .asset-hero-header { display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 1.75rem; }
    .asset-type-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; }
    .asset-type-icon.physical { background: rgba(99,102,241,0.15); color: #818cf8; }
    .asset-type-icon.digital  { background: rgba(236,72,153,0.15); color: #f472b6; }
    .asset-hero-info h2 { font-size: 1.5rem; font-weight: 800; }
    .asset-hero-info .asset-id-tag { font-family: monospace; font-size: 0.85rem; color: var(--text-muted); margin-top: 0.3rem; }

    .detail-section { margin-bottom: 1.75rem; }
    .detail-section-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid var(--border-dim); padding-bottom: 0.6rem; margin-bottom: 1rem; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-size: 0.88rem; color: var(--text-muted); }
    .detail-value { font-size: 0.92rem; font-weight: 600; text-align: right; }

    .sidebar-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }

    .borrower-section {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.2);
        border-radius: var(--radius-lg);
    }
    .borrower-avatar { width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white; font-weight: 800; }

    .depreciation-card { background: rgba(99,102,241,0.06); border-color: rgba(99,102,241,0.15); }
    .depreciation-result { background: rgba(16,185,129,0.06); border-color: rgba(16,185,129,0.15); }

    .audit-trail-block {
        background: rgba(15,23,42,0.8);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        padding: 0;
        margin-top: 0.75rem;
        overflow: hidden;
    }
    .audit-table { width: 100%; border-collapse: collapse; }
    .audit-table td {
        padding: 0.55rem 0.85rem;
        font-size: 0.82rem;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        vertical-align: top;
    }
    .audit-table td:first-child {
        color: var(--text-muted);
        font-weight: 600;
        width: 40%;
        white-space: nowrap;
    }
    .audit-table td:last-child {
        color: #a5b4fc;
        font-family: monospace;
        word-break: break-all;
    }
    .audit-table tr:last-child td { border-bottom: none; }
    .audit-section-header {
        background: rgba(99,102,241,0.08);
        padding: 0.5rem 0.85rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #818cf8;
        border-bottom: 1px solid var(--border-dim);
    }

    .method-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.2);
        color: #818cf8;
    }

    .value-bar-outer { background: rgba(255,255,255,0.05); border-radius: 30px; height: 8px; margin-top: 0.5rem; overflow: hidden; }
    .value-bar-inner { height: 100%; border-radius: 30px; transition: width 1s ease; background: linear-gradient(90deg, #6366f1, #34d399); }
</style>
@endsection

@section('content')
@php $user = Auth::user(); @endphp

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <a href="{{ route('assets.index') }}" class="back-btn" style="margin-bottom: 0;"><i class="fa-solid fa-arrow-left"></i> Back to Catalog</a>
    @if($user->role === 'admin')
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.5rem 1rem;"><i class="fa-solid fa-edit"></i> Edit</a>
            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" data-confirm="Aset ini akan dihapus secara permanen dan tidak bisa dikembalikan." data-confirm-title="Hapus Aset" data-confirm-ok="Hapus" data-confirm-type="danger" data-confirm-class="btn btn-danger">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="font-size: 0.8rem; padding: 0.5rem 1rem;"><i class="fa-solid fa-trash"></i> Hapus</button>
            </form>
        </div>
    @endif
</div>

<div class="asset-detail-grid">
    {{-- Main Asset Info --}}
    <div>
        <div class="asset-hero {{ $asset->type }}">
            <div class="asset-hero-header">
                <div class="asset-type-icon {{ $asset->type }}">
                    <i class="fa-solid {{ $asset->type === 'physical' ? 'fa-laptop' : 'fa-key' }}"></i>
                </div>
                <div class="asset-hero-info">
                    <h2>{{ $asset->name }}</h2>
                    <div class="asset-id-tag">ID: {{ $asset->id }}</div>
                    <div style="margin-top: 0.5rem;">
                        @if($asset->status === 'Available')
                            <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Available</span>
                        @elseif($asset->status === 'Borrowed')
                            <span class="badge badge-warning"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Borrowed</span>
                        @else
                            <span class="badge badge-danger"><i class="fa-solid fa-wrench"></i> Under Maintenance</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">General Information</div>
                <div class="detail-row"><span class="detail-label">Asset Type</span><span class="detail-value">{{ ucfirst($asset->type) }}</span></div>
                <div class="detail-row"><span class="detail-label">Purchase Cost</span><span class="detail-value">Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}</span></div>
                <div class="detail-row"><span class="detail-label">Purchase Date</span><span class="detail-value">{{ $asset->purchase_date->format('d F Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Age</span><span class="detail-value">{{ $asset->purchase_date->diffForHumans(null, true) }}</span></div>
            </div>

            @if($asset->type === 'physical')
            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-laptop" style="color:#818cf8;"></i> Physical Asset Details</div>
                <div class="detail-row"><span class="detail-label">Serial Number</span><span class="detail-value" style="font-family:monospace; font-size:0.85rem;">{{ $asset->detail_json['serial_number'] ?? '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Maintenance Interval</span><span class="detail-value">Every {{ $asset->detail_json['maintenance_interval'] ?? '-' }} days</span></div>
            </div>
            @else
            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-key" style="color:#f472b6;"></i> Digital License Details</div>
                @php
                    $licKey = $asset->detail_json['license_key'] ?? '';
                    $maskedKey = strlen($licKey) > 4 ? substr($licKey, 0, 4) . str_repeat('*', strlen($licKey) - 4) : '****';
                    $expiry = isset($asset->detail_json['expiry_date']) ? \Carbon\Carbon::parse($asset->detail_json['expiry_date']) : null;
                @endphp
                <div class="detail-row"><span class="detail-label">License Key</span><span class="detail-value" style="font-family:monospace; font-size:0.85rem;">{{ $maskedKey }}</span></div>
                @if($expiry)
                <div class="detail-row">
                    <span class="detail-label">Expiry Date</span>
                    <span class="detail-value {{ $expiry->isPast() ? 'style=color:#ef4444' : '' }}">
                        {{ $expiry->format('d F Y') }}
                        @if($expiry->isPast()) <span style="color:#ef4444;"> (Expired)</span>
                        @else <span style="color:#34d399;"> ({{ $expiry->diffForHumans() }})</span>
                        @endif
                    </span>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Depreciation Results (flash session) --}}
        @if(session('depreciation_results'))
        @php
            $res = session('depreciation_results');
            $remainingPct = $res['purchase_cost'] > 0 ? round(($res['remaining_value'] / $res['purchase_cost']) * 100, 1) : 0;
            $depreciatedPct = 100 - $remainingPct;
            $audit = $res['audit_trail'] ?? [];
            $details = $audit['details'] ?? [];
            $strategyName = $details['depreciation_strategy'] ?? 'Straight Line';
            $strategyDesc = $details['strategy_description'] ?? 'Garis lurus 20% per tahun';
            $ageDays = $details['calculated_age_days'] ?? 0;
            $ageYears = floor($ageDays / 365);
            $ageMonths = floor(($ageDays % 365) / 30);
            $ageRemainDays = $ageDays - ($ageYears * 365) - ($ageMonths * 30);
            $ageText = '';
            if ($ageYears > 0) $ageText .= $ageYears . ' tahun ';
            if ($ageMonths > 0) $ageText .= $ageMonths . ' bulan ';
            if ($ageRemainDays > 0 || empty($ageText)) $ageText .= $ageRemainDays . ' hari';
        @endphp
        <div class="sidebar-card depreciation-result" style="margin-top:1.5rem;">
            <div style="font-size:1rem; font-weight:700; margin-bottom:1rem; color:#34d399; display:flex; align-items:center; gap:0.5rem;">
                <i class="fa-solid fa-calculator"></i> Hasil Perhitungan Depresiasi
            </div>

            {{-- Metode yang dipakai --}}
            <div style="margin-bottom:1rem;">
                <div class="method-badge">
                    <i class="fa-solid fa-gear"></i> {{ $strategyName }}
                </div>
                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.4rem;">{{ $strategyDesc }}</div>
            </div>

            <div class="detail-row"><span class="detail-label">Nilai Awal</span><span class="detail-value">Rp {{ number_format($res['purchase_cost'], 0, ',', '.') }}</span></div>
            <div class="detail-row"><span class="detail-label">Depresiasi</span><span class="detail-value" style="color:#f87171;">- Rp {{ number_format($res['depreciation_amount'], 0, ',', '.') }}</span></div>
            <div class="detail-row"><span class="detail-label">Nilai Sisa</span><span class="detail-value" style="color:#34d399; font-size:1.1rem;">Rp {{ number_format($res['remaining_value'], 0, ',', '.') }}</span></div>

            <div style="margin-top:1rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.35rem;"><span>Retensi Nilai Aset</span><span>{{ $remainingPct }}%</span></div>
                <div class="value-bar-outer"><div class="value-bar-inner" style="width: {{ $remainingPct }}%;"></div></div>
            </div>

            {{-- Audit Trail sebagai tabel rapi --}}
            <div style="margin-top:1.5rem; font-size:0.82rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; display:flex; align-items:center; gap:0.4rem;">
                <i class="fa-solid fa-shield-halved" style="color:#818cf8;"></i> Audit Trail (Immutable)
            </div>
            <div class="audit-trail-block">
                <div class="audit-section-header">Informasi Aset</div>
                <table class="audit-table">
                    <tr><td>ID Aset</td><td>{{ $audit['asset_id'] ?? '-' }}</td></tr>
                    <tr><td>Nama</td><td>{{ $audit['name'] ?? '-' }}</td></tr>
                    <tr><td>Tipe Class</td><td style="color:#c084fc;">{{ $audit['asset_type'] ?? '-' }}</td></tr>
                    <tr><td>Dihitung Pada</td><td>{{ $audit['calculated_at'] ?? '-' }}</td></tr>
                </table>
                <div class="audit-section-header">Detail Perhitungan</div>
                <table class="audit-table">
                    <tr><td>Biaya Pembelian</td><td>Rp {{ number_format($audit['purchase_cost'] ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><td>Jumlah Depresiasi</td><td style="color:#f87171;">Rp {{ number_format($audit['depreciation_amount'] ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><td>Nilai Sisa</td><td style="color:#34d399;">Rp {{ number_format($audit['remaining_value'] ?? 0, 0, ',', '.') }}</td></tr>
                </table>
                @if(!empty($details))
                <div class="audit-section-header">Parameter Engine</div>
                <table class="audit-table">
                    @if(isset($details['serial_number']))
                    <tr><td>Serial Number</td><td>{{ $details['serial_number'] }}</td></tr>
                    @endif
                    @if(isset($details['maintenance_interval_days']))
                    <tr><td>Interval Maintenance</td><td>{{ $details['maintenance_interval_days'] }} hari</td></tr>
                    @endif
                    @if(isset($details['license_key_masked']))
                    <tr><td>License Key</td><td>{{ $details['license_key_masked'] }}</td></tr>
                    @endif
                    @if(isset($details['expiry_date']))
                    <tr><td>Tanggal Expired</td><td>{{ $details['expiry_date'] }}</td></tr>
                    @endif
                    @if(isset($details['total_license_days']))
                    <tr><td>Total Lisensi</td><td>{{ $details['total_license_days'] }} hari</td></tr>
                    @endif
                    @if(isset($details['remaining_license_days']))
                    <tr><td>Sisa Lisensi</td><td>{{ $details['remaining_license_days'] }} hari</td></tr>
                    @endif
                    <tr><td>Umur Aset</td><td>{{ trim($ageText) }}</td></tr>
                    @if(isset($details['depreciation_strategy']))
                    <tr><td>Strategy</td><td style="color:#818cf8;">{{ $details['depreciation_strategy'] }}</td></tr>
                    @endif
                    @if(isset($details['strategy_description']))
                    <tr><td>Deskripsi</td><td>{{ $details['strategy_description'] }}</td></tr>
                    @endif
                </table>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- QR Code Asset --}}
        <div class="sidebar-card" style="text-align: center;">
            <div style="font-size:0.85rem; font-weight:700; color:#c084fc; margin-bottom:0.75rem; text-align: left;"><i class="fa-solid fa-qrcode"></i> QR Code Aset</div>
            <div id="qrcode" style="display: inline-block; padding: 0.75rem; background: white; border-radius: var(--radius-md); margin-bottom: 0.5rem;"></div>
            <p style="font-size:0.75rem; color:var(--text-muted);">Scan QR Code ini untuk membuka halaman detail aset ini pada perangkat mobile.</p>
        </div>
        {{-- Borrower Info --}}
        @if($asset->status === 'Borrowed' && $asset->borrowedBy)
        <div class="sidebar-card borrower-section">
            <div style="font-size:0.85rem; font-weight:700; color:#fbbf24; margin-bottom:1rem;"><i class="fa-solid fa-user-clock"></i> Currently Borrowed By</div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <div class="borrower-avatar">{{ strtoupper(substr($asset->borrowedBy->name, 0, 1)) }}</div>
                <div>
                    <div style="font-weight:700;">{{ $asset->borrowedBy->name }}</div>
                    <div style="font-size:0.82rem; color:var(--text-muted);">{{ $asset->borrowedBy->email }}</div>
                    <span class="badge badge-warning" style="margin-top:0.3rem; font-size:0.75rem;">{{ ucfirst($asset->borrowedBy->role) }}</span>
                </div>
            </div>
            @if($user->isAdmin() || $asset->borrowed_by_id === $user->id)
            <form action="{{ route('approvals.return', $asset->id) }}" method="POST" style="margin-top: 1.25rem;" data-confirm="Konfirmasi pengembalian aset ini ke inventory." data-confirm-title="Kembalikan Aset" data-confirm-ok="Kembalikan" data-confirm-type="success" data-confirm-class="btn btn-primary">
                @csrf @method('PATCH')
                <button type="submit" class="btn" style="width:100%;justify-content:center;background:rgba(16,185,129,0.1);color:#34d399;border:1px solid rgba(16,185,129,0.25);">
                    <i class="fa-solid fa-undo"></i> Return Asset
                </button>
            </form>
            @endif
        </div>
        @endif

        {{-- Depreciation Calculator --}}
        @if($user->isAdmin() || $user->isManager())
        <div class="sidebar-card depreciation-card">
            <div style="font-size:0.85rem; font-weight:700; color:#818cf8; margin-bottom:0.6rem;"><i class="fa-solid fa-chart-line"></i> OOP Depreciation Engine</div>
            <p style="font-size:0.83rem; margin-bottom:1.25rem;">Calculate current book value using the Python FastAPI OOP microservice with <strong>Strategy Pattern</strong>. Results are logged immutably.</p>
            <form action="{{ route('assets.depreciation', $asset->id) }}" method="POST">
                @csrf
                @if($asset->type === 'physical')
                <div style="margin-bottom:0.75rem;">
                    <label style="font-size:0.78rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:0.4rem;">Metode Depresiasi (Strategy)</label>
                    <select name="depreciation_method" style="background:rgba(15,23,42,0.6); border:1px solid var(--border-dim); border-radius:var(--radius-md); color:var(--text-main); padding:0.7rem 1rem; font-size:0.9rem; outline:none; width:100%;">
                        <option value="straight_line">Garis Lurus (20%/tahun)</option>
                        <option value="declining_balance">Saldo Menurun (30%/tahun)</option>
                        <option value="sum_of_years">Jumlah Angka Tahun (SYD 5th)</option>
                    </select>
                </div>
                @endif
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <i class="fa-solid fa-calculator"></i> Calculate Depreciation
                </button>
            </form>
        </div>
        @endif

        {{-- Borrow Request (Staff) --}}
        @if($asset->status === 'Available' && $user->isStaff())
        <div class="sidebar-card">
            <div style="font-size:0.85rem; font-weight:700; color:#38bdf8; margin-bottom:0.6rem;"><i class="fa-solid fa-hand-holding"></i> Request to Borrow</div>
            <form action="{{ route('approvals.store') }}" method="POST" id="borrow-form">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                <div style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.78rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:0.4rem;">Duration (days)</label>
                    <input type="number" name="duration" class="form-control" min="1" max="365" value="7" style="background:rgba(15,23,42,0.6); border:1px solid var(--border-dim); border-radius:var(--radius-md); color:var(--text-main); padding:0.7rem 1rem; font-size:0.9rem; outline:none; width:100%;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label" style="font-size:0.78rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:0.4rem;">Reason *</label>
                    <textarea name="reason" rows="3" class="form-control" placeholder="Brief reason for borrowing…" style="background:rgba(15,23,42,0.6); border:1px solid var(--border-dim); border-radius:var(--radius-md); color:var(--text-main); padding:0.7rem 1rem; font-size:0.9rem; outline:none; width:100%; resize:vertical;" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <i class="fa-solid fa-paper-plane"></i> Submit Request
                </button>
            </form>
        </div>
        @endif

        {{-- Borrowing History --}}
        @php $reqHistory = $asset->approvalRequests()->with(['user', 'reviewedByUser'])->latest('created_at')->take(10)->get(); @endphp
        @if($reqHistory->isNotEmpty())
        <div class="sidebar-card">
            <div style="font-size:0.85rem; font-weight:700; margin-bottom:1rem;"><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);"></i> Riwayat Peminjaman</div>
            @foreach($reqHistory as $req)
            @php
                $isOverdue = $req->isOverdue();
                $isReturned = $req->returned_at !== null;
            @endphp
            <div style="padding:0.7rem 0; border-bottom:1px solid var(--border-dim);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.3rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <div style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:800;color:white;background:linear-gradient(135deg,{{ $req->user->role === 'admin' ? '#6366f1,#818cf8' : ($req->user->role === 'manager' ? '#a855f7,#c084fc' : '#0ea5e9,#38bdf8') }});flex-shrink:0;">
                            {{ strtoupper(substr($req->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div style="font-size:0.85rem; font-weight:600;">{{ $req->user->name ?? 'Unknown' }}</div>
                    </div>
                    @if($isOverdue)
                        <span class="badge badge-danger" style="font-size:0.7rem;"><i class="fa-solid fa-triangle-exclamation"></i> Overdue</span>
                    @elseif($isReturned)
                        <span class="badge" style="font-size:0.7rem;background:rgba(56,189,248,0.1);color:#38bdf8;border:1px solid rgba(56,189,248,0.2);">Dikembalikan</span>
                    @elseif($req->status === 'Pending')
                        <span class="badge badge-warning" style="font-size:0.72rem;">Pending</span>
                    @elseif($req->status === 'Approved')
                        <span class="badge badge-success" style="font-size:0.72rem;">Dipinjam</span>
                    @else
                        <span class="badge badge-danger" style="font-size:0.72rem;">Ditolak</span>
                    @endif
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted); display:flex; flex-wrap:wrap; gap:0.5rem;">
                    <span><i class="fa-solid fa-calendar"></i> {{ $req->created_at->format('d M Y') }}</span>
                    <span>{{ $req->duration }} hari</span>
                    @if($req->due_date && $req->status === 'Approved')
                        <span style="{{ $isOverdue ? 'color:#f87171;font-weight:600;' : '' }}">
                            <i class="fa-solid fa-hourglass-half"></i> Jatuh tempo: {{ $req->due_date->format('d M Y') }}
                        </span>
                    @endif
                    @if($isReturned && $req->returned_at)
                        <span style="color:#34d399;"><i class="fa-solid fa-undo"></i> {{ $req->returned_at->format('d M Y') }}</span>
                    @endif
                </div>
                @if($req->status === 'Rejected' && $req->reject_reason)
                <div style="font-size:0.72rem; color:#f87171; margin-top:0.3rem; font-style:italic;">
                    <i class="fa-solid fa-comment"></i> {{ $req->reject_reason }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var qrDiv = document.getElementById("qrcode");
        if (qrDiv) {
            new QRCode(qrDiv, {
                text: window.location.href,
                width: 128,
                height: 128,
                colorDark : "#0f172a",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });
</script>
@endsection

