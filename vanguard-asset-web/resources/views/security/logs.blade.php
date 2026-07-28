@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;"><i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Log Audit Keamanan</h1>
        <p>Catatan lengkap aktivitas sistem untuk forensik dan audit kepatuhan keamanan.</p>
    </div>
    <div>
        <a href="{{ route('security.dashboard') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

<!-- Filters -->
<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('security.logs') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">Tipe Kejadian</label>
            <select name="type" style="width: 100%; padding: 0.6rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-dim); border-radius: var(--radius-md); color: var(--text-main); outline: none;">
                <option value="">— Semua Tipe —</option>
                @foreach($eventTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ str_replace('_', ' ', strtoupper($type)) }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">Tingkat Keparahan (Severity)</label>
            <select name="severity" style="width: 100%; padding: 0.6rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-dim); border-radius: var(--radius-md); color: var(--text-main); outline: none;">
                <option value="">— Semua Tingkat —</option>
                <option value="info" {{ request('severity') == 'info' ? 'selected' : '' }}>INFO</option>
                <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>WARNING</option>
                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>CRITICAL</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Terapkan Filter</button>
            <a href="{{ route('security.logs') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Kejadian (Event)</th>
                    <th>Deskripsi</th>
                    <th>Modul</th>
                    <th>Tingkat (Severity)</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="font-size: 0.85rem; color: var(--text-muted); white-space: nowrap;">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td>
                            @if($log->user)
                                <span style="font-weight: 600;">{{ $log->user->name }}</span>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $log->user->email }}</div>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Anonim</span>
                            @endif
                        </td>
                        <td>
                            <code style="font-weight: 700; font-size: 0.85rem; color: #a5b4fc; background: rgba(99, 102, 241, 0.1); padding: 0.2rem 0.4rem; border-radius: 4px;">
                                {{ $log->event_type }}
                            </code>
                        </td>
                        <td style="font-size: 0.88rem; max-width: 300px;">
                            {{ $log->description }}
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ ucfirst($log->module ?? 'Sistem') }}
                        </td>
                        <td>
                            @if($log->severity === 'critical')
                                <span class="badge badge-danger">CRITICAL</span>
                            @elseif($log->severity === 'warning')
                                <span class="badge badge-warning">WARNING</span>
                            @else
                                <span class="badge badge-info">INFO</span>
                            @endif
                        </td>
                        <td style="font-family: monospace; font-size: 0.85rem;">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 4rem 0;">
                            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 0.75rem;"></i>
                            <p>Tidak ada catatan log keamanan yang ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    @if($logs->hasPages())
        <div style="padding: 1.5rem; display: flex; justify-content: center; border-top: 1px solid var(--border-dim);">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
