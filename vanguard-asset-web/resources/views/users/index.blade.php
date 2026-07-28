@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;"><i class="fa-solid fa-users-gear" style="color: var(--primary);"></i> Manajemen Pengguna</h1>
        <p>Kelola peran (role) pengguna dan pantau kontribusi peminjaman aset mereka.</p>
    </div>
</div>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Peran Saat Ini (Role)</th>
                    <th>Total Pengajuan</th>
                    <th>Aset Dipinjam</th>
                    <th>Ubah Peran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <span style="font-weight: 600; color: var(--text-main);">{{ $user->name }}</span>
                            @if(Auth::id() === $user->id)
                                <span style="font-size: 0.72rem; background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.5rem; font-weight: bold;">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge 
                                @if($user->role === 'admin') badge-danger
                                @elseif($user->role === 'manager') badge-warning
                                @else badge-info @endif" style="text-transform: uppercase;">
                                {{ $user->role === 'admin' ? 'IT Admin' : ($user->role === 'manager' ? 'Manajer' : 'Staf') }}
                            </span>
                        </td>
                        <td style="font-weight: 600; text-align: center;">{{ $user->approval_requests_count }}</td>
                        <td style="font-weight: 600; text-align: center;">{{ $user->borrowed_assets_count }}</td>
                        <td>
                            @if(Auth::id() === $user->id)
                                <span style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">Tidak bisa mengubah role sendiri</span>
                            @else
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" style="padding: 0.4rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-dim); border-radius: var(--radius-md); color: var(--text-main); font-size: 0.88rem; outline: none;">
                                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staf</option>
                                        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manajer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>IT Admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                        <i class="fa-solid fa-save"></i> Simpan
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
