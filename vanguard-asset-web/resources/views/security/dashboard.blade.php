@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;"><i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i> Pusat Keamanan Vanguard</h1>
        <p>Pantau metrik keamanan, aktivitas mencurigakan, dan kelola sesi pengguna secara real-time.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('security.logs') }}" class="btn btn-secondary"><i class="fa-solid fa-list-check"></i> Audit Log</a>
        <a href="{{ route('security.sessions') }}" class="btn btn-secondary"><i class="fa-solid fa-laptop-code"></i> Kelola Sesi</a>
    </div>
</div>

<!-- Threat & Security Level Alert Banner -->
<div style="background: rgba(30, 41, 59, 0.45); border: 1px solid var(--border-dim); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: relative; overflow: hidden;">
    <div style="display: flex; align-items: center; gap: 1rem; z-index: 2;">
        <div style="width: 50px; height: 50px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; 
            @if($threatLevel === 'low') background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);
            @elseif($threatLevel === 'medium') background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);
            @else background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); @endif">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.1rem;">Status Tingkat Ancaman: 
                <span style="text-transform: uppercase; font-weight: 800; 
                    @if($threatLevel === 'low') color: #10b981;
                    @elseif($threatLevel === 'medium') color: #f59e0b;
                    @else color: #ef4444; @endif">
                    {{ $threatLevel }}
                </span>
            </h3>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                @if($threatLevel === 'low')
                    Sistem dalam kondisi aman. Tidak ada aktivitas mencurigakan yang signifikan dalam 30 hari terakhir.
                @elseif($threatLevel === 'medium')
                    Waspada. Ada beberapa kegagalan login terdeteksi. Silakan periksa log aktivitas.
                @else
                    Bahaya! Terdeteksi serangan brute force atau penguncian akun berulang. Silakan cek alamat IP mencurigakan.
                @endif
            </p>
        </div>
    </div>
    <div style="text-align: right; z-index: 2;">
        <span style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600; letter-spacing: 0.5px;">Waktu Server</span>
        <h4 style="font-family: monospace; font-size: 1.1rem; color: #a5b4fc; margin-top: 0.2rem;">{{ now()->toDateTimeString() }}</h4>
    </div>
    <div style="position: absolute; right: -50px; bottom: -50px; font-size: 10rem; opacity: 0.02; pointer-events: none; z-index: 1;">
        <i class="fa-solid fa-shield-halved"></i>
    </div>
</div>

<!-- Security Stats Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Card 1: Success Logins -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 45px; height: 45px; border-radius: var(--radius-md); background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Login Sukses (30H)</span>
            <h2 style="font-size: 1.6rem; margin-top: 0.1rem;">{{ $loginSuccess }}</h2>
        </div>
    </div>

    <!-- Card 2: Failed Logins -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 45px; height: 45px; border-radius: var(--radius-md); background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Gagal Login (30H)</span>
            <h2 style="font-size: 1.6rem; margin-top: 0.1rem;">{{ $loginFailed }}</h2>
        </div>
    </div>

    <!-- Card 3: Account Locked -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 45px; height: 45px; border-radius: var(--radius-md); background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Akun Dikunci (30H)</span>
            <h2 style="font-size: 1.6rem; margin-top: 0.1rem;">{{ $accountLocked }}</h2>
        </div>
    </div>

    <!-- Card 4: Active Sessions -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 45px; height: 45px; border-radius: var(--radius-md); background: rgba(99, 102, 241, 0.1); color: #818cf8; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="fa-solid fa-network-wired"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Sesi Aktif Saat Ini</span>
            <h2 style="font-size: 1.6rem; margin-top: 0.1rem;">{{ $activeSessions }}</h2>
        </div>
    </div>
</div>

<!-- Charts and Threats Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Login Activity Chart -->
    <div class="card" style="margin-bottom: 0;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;"><i class="fa-solid fa-chart-area" style="color: var(--primary);"></i> Tren Aktivitas Login (7 Hari Terakhir)</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="loginChart"></canvas>
        </div>
    </div>

    <!-- Suspicious IPs -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;"><i class="fa-solid fa-user-ninja" style="color: #f59e0b;"></i> Top IP Mencurigakan</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Daftar alamat IP dengan jumlah kegagalan login tertinggi dalam 30 hari terakhir.</p>
        <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
            @forelse($suspiciousIps as $ip)
                <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-dim); padding: 0.85rem 1rem; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fa-solid fa-circle-exclamation" style="color: #f59e0b;"></i>
                        <span style="font-family: monospace; font-weight: 600;">{{ $ip->ip_address }}</span>
                    </div>
                    <span class="badge badge-danger" style="font-size: 0.75rem; border-radius: 4px; font-weight: 700;">{{ $ip->attempts }}x Gagal</span>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 2rem 0; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: #10b981; margin-bottom: 0.5rem;"></i>
                    <p style="font-size: 0.88rem;">Tidak ada IP mencurigakan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Critical Events -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.1rem;"><i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i> Kejadian Keamanan Kritis Terbaru</h3>
        <a href="{{ route('security.logs') }}" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 600;">Lihat Semua Log <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Kejadian</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($criticalEvents as $log)
                    <tr>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td>
                            @if($log->user)
                                <span style="font-weight: 600;">{{ $log->user->name }}</span>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $log->user->email }}</div>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Anonim</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-danger" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">
                                {{ str_replace('_', ' ', $log->event_type) }}
                            </span>
                        </td>
                        <td style="font-size: 0.88rem;">{{ $log->description }}</td>
                        <td style="font-family: monospace; font-size: 0.85rem;">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem 0;">
                            <i class="fa-solid fa-circle-check" style="font-size: 3rem; color: #10b981; margin-bottom: 0.75rem;"></i>
                            <p>Tidak ada ancaman keamanan kritis terdeteksi baru-baru ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('loginChart').getContext('2d');
        
        const successData = @json(array_column($dailyLogins, 'success'));
        const failedData = @json(array_column($dailyLogins, 'failed'));
        const labels = @json(array_column($dailyLogins, 'date'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Login Sukses',
                        data: successData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Gagal Login',
                        data: failedData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8',
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
