@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;"><i class="fa-solid fa-laptop-code" style="color: var(--primary);"></i> Manajemen Sesi Pengguna</h1>
        <p>Lihat pengguna yang sedang aktif di sistem dan batalkan/keluarkan sesi yang mencurigakan.</p>
    </div>
    <div>
        <a href="{{ route('security.dashboard') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>IP Address</th>
                    <th>User Agent / Browser</th>
                    <th>Aktivitas Terakhir</th>
                    <th>Status Sesi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td>
                            @if($session->user_name)
                                <span style="font-weight: 600;">{{ $session->user_name }}</span>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $session->user_email }}</div>
                                <span style="font-size: 0.7rem; background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); padding: 0.1rem 0.4rem; border-radius: 4px; color: #818cf8; text-transform: uppercase;">
                                    {{ $session->user_role }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Tamu (Guest / Registrasi)</span>
                            @endif
                        </td>
                        <td style="font-family: monospace; font-size: 0.88rem;">
                            {{ $session->ip_address }}
                        </td>
                        <td style="font-size: 0.82rem; max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $session->user_agent }}">
                            {{ $session->user_agent }}
                        </td>
                        <td style="font-size: 0.88rem;">
                            {{ $session->last_activity_at->diffForHumans() }}
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $session->last_activity_at->format('d M Y H:i:s') }}</div>
                        </td>
                        <td>
                            @if($session->is_current)
                                <span class="badge badge-success"><i class="fa-solid fa-circle"></i> Sesi Anda</span>
                            @else
                                <span class="badge badge-info"><i class="fa-solid fa-circle-play"></i> Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($session->is_current)
                                <button class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;" disabled>
                                    <i class="fa-solid fa-ban"></i> Hapus
                                </button>
                            @else
                                <form action="{{ route('security.sessions.destroy', $session->id) }}" method="POST" data-confirm="Sesi user ini akan dibatalkan secara paksa." data-confirm-title="Paksa Keluar" data-confirm-ok="Ya, Paksa Keluar" data-confirm-type="danger" data-confirm-class="btn btn-danger">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                        <i class="fa-solid fa-sign-out-alt"></i> Force Logout
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
