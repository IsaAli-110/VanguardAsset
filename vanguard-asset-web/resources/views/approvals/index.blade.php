@extends('layouts.app')

@section('styles')
<style>
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; }
    .page-header p { color: var(--text-muted); margin-top: 0.2rem; }

    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-chip {
        background: var(--bg-card); backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim); border-radius: var(--radius-lg);
        padding: 1.25rem; text-align: center; transition: var(--transition-smooth);
    }
    .stat-chip:hover { border-color: var(--border-glow); transform: translateY(-2px); }
    .stat-chip .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-chip .lbl { font-size: 0.72rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.4rem; }
    .stat-chip.pending .num { color: #fbbf24; }
    .stat-chip.active .num { color: #34d399; }
    .stat-chip.overdue .num { color: #f87171; }
    .stat-chip.completed .num { color: #38bdf8; }

    .tab-bar { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .tab-btn { background: transparent; border: 1px solid var(--border-dim); color: var(--text-muted); padding: 0.55rem 1.1rem; border-radius: 30px; font-size: 0.88rem; font-weight: 600; cursor: pointer; transition: var(--transition-smooth); display: inline-flex; align-items: center; gap: 0.5rem; }
    .tab-btn:hover, .tab-btn.active { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.35); color: #818cf8; }

    .request-card {
        background: var(--bg-card); backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim); border-radius: var(--radius-lg);
        margin-bottom: 1rem; transition: var(--transition-smooth); overflow: hidden;
    }
    .request-card:hover { border-color: var(--border-glow); }
    .request-card.is-overdue { border-color: rgba(239,68,68,0.35); }
    .request-card.is-overdue::before {
        content: ''; display: block; height: 3px;
        background: linear-gradient(90deg, #ef4444, #f59e0b);
    }

    .req-main { padding: 1.25rem 1.5rem; }
    .req-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
    .req-left { flex: 1; }
    .req-asset-name { font-weight: 700; font-size: 1rem; }
    .req-asset-name a { color: inherit; text-decoration: none; }
    .req-asset-name a:hover { color: var(--primary); }
    .req-asset-id { font-family: monospace; font-size: 0.78rem; color: var(--text-muted); }
    .req-requester { display: flex; align-items: center; gap: 0.6rem; margin-top: 0.5rem; }
    .req-avatar { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: white; flex-shrink: 0; }
    .req-avatar.staff { background: linear-gradient(135deg, #0ea5e9, #38bdf8); }
    .req-avatar.admin { background: linear-gradient(135deg, #6366f1, #818cf8); }
    .req-avatar.manager { background: linear-gradient(135deg, #a855f7, #c084fc); }
    .req-name { font-size: 0.88rem; font-weight: 600; }
    .req-role { font-size: 0.7rem; color: var(--text-muted); }

    .req-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }

    .status-pill { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
    .status-pill.pending { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
    .status-pill.approved { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
    .status-pill.rejected { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
    .status-pill.returned { background: rgba(56,189,248,0.12); color: #38bdf8; border: 1px solid rgba(56,189,248,0.25); }
    .status-pill.overdue { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); animation: pulseOverdue 2s infinite; }
    @keyframes pulseOverdue { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,0.3);} 50%{box-shadow:0 0 0 6px rgba(239,68,68,0);} }

    .due-info { font-size: 0.78rem; color: var(--text-muted); text-align: right; }
    .due-info.overdue { color: #f87171; font-weight: 600; }

    .req-details { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
    @media(max-width: 600px) { .req-details { grid-template-columns: 1fr; } }
    .detail-chip { background: rgba(255,255,255,0.03); border-radius: var(--radius-md); padding: 0.6rem 0.85rem; }
    .detail-chip .chip-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .detail-chip .chip-value { font-size: 0.88rem; font-weight: 600; margin-top: 0.15rem; }

    .reason-block { background: rgba(255,255,255,0.02); border-left: 3px solid var(--border-dim); padding: 0.6rem 0.85rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1rem; }
    .reason-block .reason-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem; }
    .reason-block .reason-text { font-size: 0.85rem; color: var(--text-muted); }
    .reason-block.reject-reason { border-left-color: #f87171; background: rgba(239,68,68,0.05); }

    /* Timeline */
    .timeline { display: flex; align-items: center; gap: 0; margin-bottom: 1rem; padding: 0.5rem 0; }
    .tl-step { display: flex; flex-direction: column; align-items: center; position: relative; flex: 0 0 auto; }
    .tl-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; z-index: 1; }
    .tl-dot.done { background: rgba(16,185,129,0.15); color: #34d399; border: 2px solid rgba(16,185,129,0.4); }
    .tl-dot.current { background: rgba(99,102,241,0.15); color: #818cf8; border: 2px solid rgba(99,102,241,0.4); animation: pulseDot 2s infinite; }
    .tl-dot.rejected-dot { background: rgba(239,68,68,0.15); color: #f87171; border: 2px solid rgba(239,68,68,0.4); }
    .tl-dot.waiting { background: rgba(255,255,255,0.04); color: var(--text-muted); border: 2px solid rgba(255,255,255,0.08); }
    @keyframes pulseDot { 0%,100%{box-shadow:0 0 0 0 rgba(99,102,241,0.3);} 50%{box-shadow:0 0 0 5px rgba(99,102,241,0);} }
    .tl-label { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.35rem; white-space: nowrap; text-align: center; }
    .tl-date { font-size: 0.65rem; color: rgba(255,255,255,0.25); }
    .tl-line { flex: 1; height: 2px; min-width: 30px; margin: 0 0.2rem; }
    .tl-line.done { background: rgba(16,185,129,0.3); }
    .tl-line.pending-line { background: rgba(255,255,255,0.06); }

    .actions-row { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
    .btn-sm { padding: 0.45rem 0.9rem; font-size: 0.82rem; border-radius: var(--radius-md); }

    .reject-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; }
    .reject-modal.open { display: flex; }
    .reject-modal-content {
        background: var(--bg-card); backdrop-filter: blur(20px);
        border: 1px solid var(--border-dim); border-radius: var(--radius-lg);
        padding: 2rem; width: 90%; max-width: 420px;
    }
    .reject-modal-content h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .reject-modal-content textarea {
        width: 100%; background: rgba(15,23,42,0.6); border: 1px solid var(--border-dim);
        border-radius: var(--radius-md); color: var(--text-main); padding: 0.7rem; font-size: 0.9rem;
        resize: vertical; outline: none; margin-bottom: 1rem;
    }

    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.2; }
</style>
@endsection

@section('content')
@php $user = Auth::user(); @endphp

<div class="page-header">
    <div>
        <h1><i class="fa-solid fa-list-check" style="color: var(--primary);"></i>
            @if($user->isAdmin() || $user->isManager()) Approval Center @else My Borrow Requests @endif
        </h1>
        <p>
            @if($user->isAdmin() || $user->isManager()) Kelola dan review permintaan peminjaman aset dari karyawan
            @else Pantau status permintaan peminjaman aset Anda @endif
        </p>
    </div>
</div>

{{-- Stats Row --}}
@php
    $pendingCount = $requests->where('status', 'Pending')->count();
    $activeCount = $requests->where('status', 'Approved')->where('returned_at', null)->count();
    $overdueCount = $requests->filter(fn($r) => $r->isOverdue())->count();
    $completedCount = $requests->whereNotNull('returned_at')->count();
@endphp
<div class="stats-row">
    <div class="stat-chip pending"><div class="num" data-count="{{ $pendingCount }}">0</div><div class="lbl">Pending</div></div>
    <div class="stat-chip active"><div class="num" data-count="{{ $activeCount }}">0</div><div class="lbl">Sedang Dipinjam</div></div>
    <div class="stat-chip overdue"><div class="num" data-count="{{ $overdueCount }}">0</div><div class="lbl">Overdue</div></div>
    <div class="stat-chip completed"><div class="num" data-count="{{ $completedCount }}">0</div><div class="lbl">Selesai</div></div>
</div>

{{-- Tab Filter --}}
<div class="tab-bar">
    <button class="tab-btn active" onclick="filterRequests('all', this)"><i class="fa-solid fa-list"></i> Semua ({{ $requests->count() }})</button>
    <button class="tab-btn" onclick="filterRequests('Pending', this)"><i class="fa-solid fa-clock"></i> Pending ({{ $pendingCount }})</button>
    <button class="tab-btn" onclick="filterRequests('Approved', this)"><i class="fa-solid fa-arrow-right-arrow-left"></i> Dipinjam ({{ $activeCount }})</button>
    <button class="tab-btn" onclick="filterRequests('overdue', this)"><i class="fa-solid fa-triangle-exclamation"></i> Overdue ({{ $overdueCount }})</button>
    <button class="tab-btn" onclick="filterRequests('Returned', this)"><i class="fa-solid fa-check-double"></i> Selesai ({{ $completedCount }})</button>
    <button class="tab-btn" onclick="filterRequests('Rejected', this)"><i class="fa-solid fa-times"></i> Ditolak ({{ $requests->where('status','Rejected')->count() }})</button>
</div>

{{-- Requests List --}}
<div id="requests-container">
    @forelse($requests as $req)
    @php
        $isOverdue = $req->isOverdue();
        $isReturned = $req->returned_at !== null;
        $filterStatus = $req->status;
        if ($isOverdue) $filterStatus = 'overdue';
        if ($isReturned) $filterStatus = 'Returned';
    @endphp
    <div class="request-card {{ $isOverdue ? 'is-overdue' : '' }}" data-status="{{ $filterStatus }}">
        <div class="req-main">
            <div class="req-top">
                <div class="req-left">
                    {{-- Asset Info --}}
                    <div class="req-asset-name">
                        <a href="{{ route('assets.show', $req->asset_id) }}">
                            <i class="fa-solid {{ ($req->asset && $req->asset->type === 'physical') ? 'fa-laptop' : 'fa-key' }}" style="color: {{ $req->asset && $req->asset->type === 'digital' ? '#f472b6' : '#818cf8' }}; margin-right: 0.3rem;"></i>
                            {{ $req->asset->name ?? 'Unknown Asset' }}
                        </a>
                    </div>
                    <div class="req-asset-id">{{ $req->asset_id }}</div>

                    {{-- Requester (for admin/manager) --}}
                    @if($user->isAdmin() || $user->isManager())
                    <div class="req-requester">
                        <div class="req-avatar {{ $req->user->role }}">{{ strtoupper(substr($req->user->name, 0, 1)) }}</div>
                        <div>
                            <div class="req-name">{{ $req->user->name }}</div>
                            <div class="req-role">{{ $req->user->email }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="req-right">
                    @if($isOverdue)
                        <span class="status-pill overdue"><i class="fa-solid fa-triangle-exclamation"></i> Overdue {{ $req->overdueDays() }} hari</span>
                    @elseif($isReturned)
                        <span class="status-pill returned"><i class="fa-solid fa-check-double"></i> Dikembalikan</span>
                    @elseif($req->status === 'Pending')
                        <span class="status-pill pending"><i class="fa-solid fa-clock"></i> Pending</span>
                    @elseif($req->status === 'Approved')
                        <span class="status-pill approved"><i class="fa-solid fa-arrow-right-arrow-left"></i> Dipinjam</span>
                    @else
                        <span class="status-pill rejected"><i class="fa-solid fa-times"></i> Ditolak</span>
                    @endif

                    @if($req->due_date && !$isReturned && $req->status === 'Approved')
                        <div class="due-info {{ $isOverdue ? 'overdue' : '' }}">
                            <i class="fa-solid fa-calendar"></i>
                            Jatuh tempo: {{ $req->due_date->format('d M Y') }}
                            @if(!$isOverdue)
                                ({{ now()->diffInDays($req->due_date) }} hari lagi)
                            @endif
                        </div>
                    @endif
                    @if($isReturned && $req->returned_at)
                        <div class="due-info">
                            <i class="fa-solid fa-undo"></i> Dikembalikan: {{ $req->returned_at->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Details --}}
            <div class="req-details">
                <div class="detail-chip">
                    <div class="chip-label">Durasi Pinjam</div>
                    <div class="chip-value">{{ $req->duration }} hari</div>
                </div>
                @if($req->borrowed_at)
                <div class="detail-chip">
                    <div class="chip-label">Tanggal Pinjam</div>
                    <div class="chip-value">{{ $req->borrowed_at->format('d M Y, H:i') }}</div>
                </div>
                @endif
            </div>

            {{-- Reason --}}
            <div class="reason-block">
                <div class="reason-label">Alasan Peminjaman</div>
                <div class="reason-text">{{ $req->reason }}</div>
            </div>

            {{-- Reject Reason --}}
            @if($req->status === 'Rejected' && $req->reject_reason)
            <div class="reason-block reject-reason">
                <div class="reason-label"><i class="fa-solid fa-circle-exclamation" style="color:#f87171;"></i> Alasan Penolakan</div>
                <div class="reason-text" style="color:#f87171;">{{ $req->reject_reason }}</div>
                @if($req->reviewedByUser)
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.4rem;">— {{ $req->reviewedByUser->name }}</div>
                @endif
            </div>
            @endif

            {{-- Timeline --}}
            <div class="timeline">
                {{-- Step 1: Requested --}}
                <div class="tl-step">
                    <div class="tl-dot done"><i class="fa-solid fa-paper-plane"></i></div>
                    <div class="tl-label">Dikirim<br><span class="tl-date">{{ $req->created_at->format('d M') }}</span></div>
                </div>
                <div class="tl-line {{ $req->status !== 'Pending' ? 'done' : 'pending-line' }}"></div>

                {{-- Step 2: Reviewed --}}
                <div class="tl-step">
                    @if($req->status === 'Pending')
                        <div class="tl-dot waiting"><i class="fa-solid fa-clock"></i></div>
                        <div class="tl-label">Review<br><span class="tl-date">Menunggu</span></div>
                    @elseif($req->status === 'Rejected')
                        <div class="tl-dot rejected-dot"><i class="fa-solid fa-times"></i></div>
                        <div class="tl-label">Ditolak<br><span class="tl-date">{{ $req->updated_at->format('d M') }}</span></div>
                    @else
                        <div class="tl-dot done"><i class="fa-solid fa-check"></i></div>
                        <div class="tl-label">Disetujui<br><span class="tl-date">{{ $req->borrowed_at ? $req->borrowed_at->format('d M') : $req->updated_at->format('d M') }}</span></div>
                    @endif
                </div>
                @if($req->status === 'Approved')
                <div class="tl-line {{ $isReturned ? 'done' : 'pending-line' }}"></div>
                {{-- Step 3: Borrowing / Returned --}}
                <div class="tl-step">
                    @if($isReturned)
                        <div class="tl-dot done"><i class="fa-solid fa-undo"></i></div>
                        <div class="tl-label">Dikembalikan<br><span class="tl-date">{{ $req->returned_at->format('d M') }}</span></div>
                    @elseif($isOverdue)
                        <div class="tl-dot rejected-dot"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="tl-label" style="color:#f87171;">Overdue<br><span class="tl-date">{{ $req->overdueDays() }} hari</span></div>
                    @else
                        <div class="tl-dot current"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                        <div class="tl-label">Sedang Dipinjam<br><span class="tl-date">{{ now()->diffInDays($req->due_date) }} hari lagi</span></div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Actions --}}
            @if($req->status === 'Pending' && ($user->isAdmin() || $user->isManager()))
            <div class="actions-row">
                <form action="{{ route('approvals.approve', $req->id) }}" method="POST" data-confirm="Setujui peminjaman aset ini? Aset akan langsung berstatus Dipinjam." data-confirm-title="Setujui Peminjaman" data-confirm-ok="Setujui" data-confirm-type="success" data-confirm-class="btn btn-primary">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-check"></i> Setujui
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-sm" onclick="openRejectModal({{ $req->id }})">
                    <i class="fa-solid fa-times"></i> Tolak
                </button>
            </div>
            @endif

            @if($req->status === 'Approved' && !$isReturned && ($user->isAdmin() || $req->user_id === $user->id))
            <div class="actions-row">
                <form action="{{ route('approvals.return', $req->asset_id) }}" method="POST" data-confirm="Konfirmasi pengembalian aset ini ke inventory." data-confirm-title="Kembalikan Aset" data-confirm-ok="Kembalikan" data-confirm-type="success" data-confirm-class="btn btn-primary">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#34d399;border:1px solid rgba(16,185,129,0.25);">
                        <i class="fa-solid fa-undo"></i> Kembalikan Aset
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fa-solid fa-inbox"></i>
        <p style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem;">Belum ada permintaan peminjaman</p>
        <p>{{ $user->isStaff() ? 'Jelajahi katalog aset untuk mengajukan permintaan peminjaman.' : 'Belum ada permintaan dari karyawan.' }}</p>
        @if($user->isStaff())
        <a href="{{ route('assets.index') }}" class="btn btn-primary" style="margin-top:1.25rem; display:inline-flex;">
            <i class="fa-solid fa-boxes-stacked"></i> Jelajahi Aset
        </a>
        @endif
    </div>
    @endforelse
</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-modal-content">
        <h3><i class="fa-solid fa-circle-xmark" style="color:#f87171;"></i> Tolak Permintaan</h3>
        <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">Berikan alasan penolakan agar peminjam memahami keputusan Anda.</p>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <textarea name="reject_reason" rows="3" placeholder="Alasan penolakan (opsional)..."></textarea>
            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-times"></i> Tolak Permintaan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterRequests(status, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.request-card').forEach(card => {
        card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
    });
}

function openRejectModal(requestId) {
    const form = document.getElementById('rejectForm');
    form.action = '/approvals/' + requestId + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
}

// Close modal on backdrop click
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endsection
