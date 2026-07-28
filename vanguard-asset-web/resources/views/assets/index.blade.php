@extends('layouts.app')

@section('styles')
<style>
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; }
    .page-header p { color: var(--text-muted); margin-top: 0.25rem; }

    .filter-bar {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-bar input[type="text"] {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        color: var(--text-main);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        outline: none;
        flex: 1;
        min-width: 200px;
        transition: var(--transition-smooth);
    }
    .filter-bar input[type="text"]:focus { border-color: var(--primary); }
    .filter-bar select {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        color: var(--text-main);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
    }
    .filter-bar select option { background: #1e293b; }

    .assets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .asset-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .asset-card:hover {
        transform: translateY(-5px);
        border-color: var(--border-glow);
        box-shadow: 0 15px 35px -10px rgba(0,0,0,0.5);
    }
    .asset-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        transition: var(--transition-smooth);
    }
    .asset-card.physical::before { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .asset-card.digital::before  { background: linear-gradient(135deg, #ec4899, #d946ef); }

    .asset-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem; }
    .asset-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .asset-icon.physical { background: rgba(99,102,241,0.15); color: #818cf8; }
    .asset-icon.digital  { background: rgba(236,72,153,0.15); color: #f472b6; }

    .asset-name { font-size: 1.05rem; font-weight: 700; line-height: 1.3; }
    .asset-id   { font-size: 0.78rem; color: var(--text-muted); font-family: monospace; margin-top: 0.15rem; }

    .asset-meta { display: flex; flex-direction: column; gap: 0.4rem; }
    .meta-row { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted); }
    .meta-row i { width: 14px; text-align: center; opacity: 0.7; }
    .meta-row span { color: var(--text-main); }

    .borrower-chip {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #fbbf24;
    }

    .asset-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: auto; }

    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); grid-column: 1 / -1; }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.2; display: block; }

    /* ─── Custom Modal Glassmorphism ─── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(6px);
        z-index: 9000;
        align-items: center;
        justify-content: center;
        animation: fadeInOverlay 0.25s ease;
    }
    .modal-overlay.active { display: flex; }

    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .modal-box {
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 20px;
        padding: 2.25rem;
        width: 100%;
        max-width: 480px;
        margin: 1rem;
        position: relative;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8);
        animation: slideUpModal 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideUpModal {
        from { opacity: 0; transform: translateY(40px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(135deg, #6366f1, #c084fc);
        border-radius: 20px 20px 0 0;
    }

    .modal-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .modal-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        color: #818cf8;
        flex-shrink: 0;
    }
    .modal-title { font-size: 1.2rem; font-weight: 700; }
    .modal-subtitle { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem; }

    .modal-close {
        position: absolute;
        top: 1.25rem; right: 1.25rem;
        background: rgba(255,255,255,0.07);
        border: none;
        color: var(--text-muted);
        width: 32px; height: 32px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition-smooth);
    }
    .modal-close:hover { background: rgba(239,68,68,0.15); color: #f87171; }

    .modal-form-group { margin-bottom: 1.25rem; }
    .modal-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    .modal-input {
        width: 100%;
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        color: var(--text-main);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        outline: none;
        transition: var(--transition-smooth);
    }
    .modal-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }

    .modal-footer { display: flex; gap: 0.75rem; margin-top: 1.75rem; }
    .modal-footer .btn { flex: 1; justify-content: center; }
</style>
@endsection

@section('content')
@php $user = Auth::user(); @endphp

<div class="page-header">
    <div>
        <h1><i class="fa-solid fa-boxes-stacked" style="color: var(--primary);"></i> Katalog Aset</h1>
        <p>{{ $user->isStaff() ? 'Telusuri aset tersedia atau pantau status peminjaman Anda' : 'Kelola seluruh aset perusahaan di semua divisi' }}</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        @if($user->role === 'admin' || $user->role === 'manager')
            <a href="{{ route('assets.export.csv') }}" class="btn btn-secondary">
                <i class="fa-solid fa-file-csv"></i> Ekspor CSV
            </a>
            <a href="{{ route('assets.export.pdf') }}" class="btn btn-secondary" style="border-color: rgba(239,68,68,0.3); color: #f87171;">
                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
            </a>
        @endif
        @if($user->role === 'admin')
            <a href="{{ route('assets.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Aset Baru
            </a>
        @endif
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
    <input type="text" id="search-input" placeholder="Cari berdasarkan nama atau ID aset…">
    <select id="filter-type">
        <option value="">Semua Tipe</option>
        <option value="physical">Fisik</option>
        <option value="digital">Digital</option>
    </select>
    <select id="filter-status">
        <option value="">Semua Status</option>
        <option value="Available">Tersedia</option>
        <option value="Borrowed">Dipinjam</option>
        <option value="Under Maintenance">Dalam Perawatan</option>
    </select>
</div>

{{-- Assets Grid --}}
<div class="assets-grid" id="assets-grid">
    @forelse($assets as $asset)
    <div class="asset-card {{ $asset->type }}" data-name="{{ strtolower($asset->name) }}" data-id="{{ strtolower($asset->id) }}" data-type="{{ $asset->type }}" data-status="{{ $asset->status }}">
        <div class="asset-header">
            <div class="asset-icon {{ $asset->type }}">
                <i class="fa-solid {{ $asset->type === 'physical' ? 'fa-laptop' : 'fa-key' }}"></i>
            </div>
            @if($asset->status === 'Available')
                <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Tersedia</span>
            @elseif($asset->status === 'Borrowed')
                <span class="badge badge-warning"><i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Dipinjam</span>
            @else
                <span class="badge badge-danger"><i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Dalam Perawatan</span>
            @endif
        </div>

        <div>
            <div class="asset-name">{{ $asset->name }}</div>
            <div class="asset-id">{{ $asset->id }}</div>
        </div>

        <div class="asset-meta">
            <div class="meta-row">
                <i class="fa-solid fa-tag"></i>
                <span>Aset {{ $asset->type === 'physical' ? 'Fisik' : 'Digital' }}</span>
            </div>
            <div class="meta-row">
                <i class="fa-solid fa-money-bill"></i>
                <span>Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}</span>
            </div>
            <div class="meta-row">
                <i class="fa-solid fa-calendar"></i>
                <span>Dibeli {{ $asset->purchase_date->format('d M Y') }}</span>
            </div>
        </div>

        @if($asset->status === 'Borrowed' && $asset->borrowedBy)
        <div class="borrower-chip">
            <i class="fa-solid fa-user"></i>
            <div>
                <div style="font-weight: 600;">{{ $asset->borrowedBy->name }}</div>
                <div style="font-size: 0.75rem; opacity: 0.8;">Peminjam Saat Ini</div>
            </div>
        </div>
        @endif

        <div class="asset-actions">
            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-secondary" style="flex:1; justify-content: center;">
                <i class="fa-solid fa-eye"></i> Detail
            </a>
            @if($asset->status === 'Available' && $user->isStaff())
            <button
                type="button"
                class="btn btn-primary"
                style="flex:1; justify-content:center;"
                onclick="openBorrowModal('{{ $asset->id }}', '{{ addslashes($asset->name) }}')"
            >
                <i class="fa-solid fa-hand-holding"></i> Ajukan Pinjam
            </button>
            @endif
            @if($asset->status === 'Borrowed' && ($user->isAdmin() || $asset->borrowed_by_id === $user->id))
            <form action="{{ route('approvals.return', $asset->id) }}" method="POST" style="flex:1;" data-confirm="Konfirmasi pengembalian aset ini." data-confirm-title="Kembalikan Aset" data-confirm-ok="Kembalikan" data-confirm-type="success" data-confirm-class="btn btn-primary">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-secondary" style="width:100%; justify-content:center; border-color: rgba(16,185,129,0.3); color: #34d399;">
                    <i class="fa-solid fa-undo"></i> Kembalikan
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fa-solid fa-box-open"></i>
        <p style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">Tidak ada aset ditemukan</p>
        <p>{{ $user->isAdmin() ? 'Mulai dengan menambahkan aset baru.' : 'Belum ada aset yang tersedia saat ini.' }}</p>
    </div>
    @endforelse
</div>

{{-- ─── Custom Borrow Modal ─── --}}
<div class="modal-overlay" id="borrowModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeBorrowModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fa-solid fa-hand-holding"></i>
            </div>
            <div>
                <div class="modal-title">Ajukan Peminjaman Aset</div>
                <div class="modal-subtitle" id="modal-asset-name">—</div>
            </div>
        </div>

        <form action="{{ route('approvals.store') }}" method="POST" id="borrowForm">
            @csrf
            <input type="hidden" name="asset_id" id="modal-asset-id">

            <div class="modal-form-group">
                <label class="modal-label" for="modal-duration">
                    <i class="fa-solid fa-calendar-days"></i> Durasi Peminjaman (Hari)
                </label>
                <input
                    type="number"
                    id="modal-duration"
                    name="duration"
                    class="modal-input"
                    placeholder="Contoh: 7"
                    min="1"
                    max="365"
                    required
                >
            </div>

            <div class="modal-form-group">
                <label class="modal-label" for="modal-reason">
                    <i class="fa-solid fa-comment-dots"></i> Alasan Peminjaman
                </label>
                <textarea
                    id="modal-reason"
                    name="reason"
                    class="modal-input"
                    rows="3"
                    placeholder="Jelaskan tujuan dan kebutuhan peminjaman aset ini…"
                    required
                    style="resize: vertical; min-height: 90px;"
                ></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBorrowModal()">
                    <i class="fa-solid fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ─── Custom Modal Logic ───
function openBorrowModal(assetId, assetName) {
    document.getElementById('modal-asset-id').value = assetId;
    document.getElementById('modal-asset-name').textContent = assetName;
    document.getElementById('modal-duration').value = '';
    document.getElementById('modal-reason').value = '';
    document.getElementById('borrowModal').classList.add('active');
    setTimeout(() => document.getElementById('modal-duration').focus(), 300);
}

function closeBorrowModal() {
    document.getElementById('borrowModal').classList.remove('active');
}

// Tutup modal jika klik di luar box
document.getElementById('borrowModal').addEventListener('click', function(e) {
    if (e.target === this) closeBorrowModal();
});

// ─── Filter & Search Logic ───
const searchInput  = document.getElementById('search-input');
const filterType   = document.getElementById('filter-type');
const filterStatus = document.getElementById('filter-status');

function applyFilters() {
    const q = searchInput.value.toLowerCase();
    const t = filterType.value.toLowerCase();
    const s = filterStatus.value;
    document.querySelectorAll('.asset-card').forEach(card => {
        const matchQ = card.dataset.name.includes(q) || card.dataset.id.includes(q);
        const matchT = !t || card.dataset.type === t;
        const matchS = !s || card.dataset.status === s;
        card.style.display = (matchQ && matchT && matchS) ? '' : 'none';
    });
}

searchInput.addEventListener('input', applyFilters);
filterType.addEventListener('change', applyFilters);
filterStatus.addEventListener('change', applyFilters);
</script>
@endsection
