@extends('layouts.app')

@section('styles')
<style>
    .page-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; }
    .page-header p { color: var(--text-muted); margin-top: 0.2rem; }
    .back-btn { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: var(--transition-smooth); }
    .back-btn:hover { color: var(--text-main); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media(max-width: 700px){ .form-grid { grid-template-columns: 1fr; } }

    .form-group { margin-bottom: 0; display: flex; flex-direction: column; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control {
        background: rgba(15,23,42,0.6);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        color: var(--text-main);
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        outline: none;
        transition: var(--transition-smooth);
        width: 100%;
    }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 10px rgba(99,102,241,0.15); }
    .form-control:disabled { background: rgba(15, 23, 42, 0.3); color: var(--text-muted); cursor: not-allowed; border-color: transparent; }
    .form-control::placeholder { color: var(--text-muted); opacity: 0.6; }

    .type-section {
        background: rgba(99,102,241,0.05);
        border: 1px solid rgba(99,102,241,0.12);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        grid-column: 1 / -1;
    }
    .type-section-title { font-size: 0.85rem; font-weight: 700; color: #818cf8; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .type-section-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    @media(max-width: 700px) { .type-section-grid { grid-template-columns: 1fr; } }

    .digital-section {
        background: rgba(236,72,153,0.05);
        border-color: rgba(236,72,153,0.12);
    }
    .digital-section .type-section-title { color: #f472b6; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('assets.show', $asset->id) }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Asset Details</a>
</div>

<div class="page-header">
    <div style="width:48px;height:48px;background:rgba(99,102,241,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#818cf8;">
        <i class="fa-solid fa-edit"></i>
    </div>
    <div>
        <h1>Edit Asset</h1>
        <p>Update asset details for {{ $asset->name }}</p>
    </div>
</div>

<div class="card" style="padding: 2.25rem;">
    <form action="{{ route('assets.update', $asset->id) }}" method="POST" id="asset-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            {{-- Asset ID (ReadOnly) --}}
            <div class="form-group">
                <label class="form-label">Asset ID</label>
                <input type="text" class="form-control" value="{{ $asset->id }}" disabled>
            </div>

            {{-- Asset Type (ReadOnly) --}}
            <div class="form-group">
                <label class="form-label">Asset Type</label>
                <input type="text" class="form-control" value="{{ ucfirst($asset->type) }} Asset" disabled>
            </div>

            {{-- Asset Name --}}
            <div class="form-group full-width">
                <label class="form-label" for="name">Asset Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. MacBook Pro 14-inch M3" value="{{ old('name', $asset->name) }}" required>
                @error('name')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
            </div>

            {{-- Purchase Cost --}}
            <div class="form-group">
                <label class="form-label" for="purchase_cost">Purchase Cost (Rp) *</label>
                <input type="number" id="purchase_cost" name="purchase_cost" class="form-control" placeholder="e.g. 48500000" min="0.01" step="0.01" value="{{ old('purchase_cost', $asset->purchase_cost) }}" required>
                @error('purchase_cost')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
            </div>

            {{-- Purchase Date --}}
            <div class="form-group">
                <label class="form-label" for="purchase_date">Purchase Date *</label>
                <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date->format('Y-m-d')) }}" required>
                @error('purchase_date')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
            </div>

            {{-- Physical Fields --}}
            @if($asset->type === 'physical')
            <div class="type-section" id="physical-section">
                <div class="type-section-title"><i class="fa-solid fa-laptop"></i> Physical Asset Details</div>
                <div class="type-section-grid">
                    <div class="form-group">
                        <label class="form-label" for="serial_number">Serial Number *</label>
                        <input type="text" id="serial_number" name="serial_number" class="form-control" placeholder="e.g. C02XYZ123MBP" value="{{ old('serial_number', $asset->detail_json['serial_number'] ?? '') }}" required>
                        @error('serial_number')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="maintenance_interval">Maintenance Interval (days) *</label>
                        <input type="number" id="maintenance_interval" name="maintenance_interval" class="form-control" placeholder="e.g. 180" min="1" value="{{ old('maintenance_interval', $asset->detail_json['maintenance_interval'] ?? '') }}" required>
                        @error('maintenance_interval')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            @else
            {{-- Digital Fields --}}
            <div class="type-section digital-section" id="digital-section">
                <div class="type-section-title"><i class="fa-solid fa-key"></i> Digital License Details</div>
                <div class="type-section-grid">
                    <div class="form-group">
                        <label class="form-label" for="license_key">License Key *</label>
                        <input type="text" id="license_key" name="license_key" class="form-control" placeholder="e.g. XXXX-XXXX-XXXX-XXXX" value="{{ old('license_key', $asset->detail_json['license_key'] ?? '') }}" required>
                        @error('license_key')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="expiry_date">License Expiry Date *</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date', isset($asset->detail_json['expiry_date']) ? \Carbon\Carbon::parse($asset->detail_json['expiry_date'])->format('Y-m-d') : '') }}" required>
                        @error('expiry_date')<div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
