<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    /**
     * Security Dashboard — metrics & charts.
     */
    public function dashboard()
    {
        // Login stats (last 30 days)
        $loginSuccess = SecurityLog::where('event_type', 'login_success')
            ->where('created_at', '>=', now()->subDays(30))->count();
        $loginFailed = SecurityLog::where('event_type', 'login_failed')
            ->where('created_at', '>=', now()->subDays(30))->count();
        $accountLocked = SecurityLog::where('event_type', 'account_locked')
            ->where('created_at', '>=', now()->subDays(30))->count();

        // Active sessions
        $activeSessions = DB::table('sessions')->whereNotNull('user_id')->count();

        // Top suspicious IPs (most failed logins)
        $suspiciousIps = SecurityLog::where('event_type', 'login_failed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('ip_address', DB::raw('COUNT(*) as attempts'))
            ->groupBy('ip_address')
            ->orderByDesc('attempts')
            ->take(5)
            ->get();

        // Recent critical events
        $criticalEvents = SecurityLog::where('severity', 'critical')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Daily login chart data (last 7 days)
        $dailyLogins = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dailyLogins[] = [
                'date' => now()->subDays($i)->format('d M'),
                'success' => SecurityLog::where('event_type', 'login_success')
                    ->whereDate('created_at', $date)->count(),
                'failed' => SecurityLog::where('event_type', 'login_failed')
                    ->whereDate('created_at', $date)->count(),
            ];
        }

        // Threat level
        $threatLevel = 'low';
        if ($accountLocked > 0 || $loginFailed > 20) $threatLevel = 'medium';
        if ($accountLocked > 5 || $loginFailed > 50) $threatLevel = 'high';

        // Total events
        $totalEvents = SecurityLog::where('created_at', '>=', now()->subDays(30))->count();

        return view('security.dashboard', compact(
            'loginSuccess', 'loginFailed', 'accountLocked', 'activeSessions',
            'suspiciousIps', 'criticalEvents', 'dailyLogins', 'threatLevel', 'totalEvents'
        ));
    }

    /**
     * Audit logs page.
     */
    public function logs(Request $request)
    {
        $query = SecurityLog::with('user')->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $logs = $query->paginate(25);

        $eventTypes = SecurityLog::select('event_type')->distinct()->pluck('event_type');

        return view('security.logs', compact('logs', 'eventTypes'));
    }

    /**
     * Session management page.
     */
    public function sessions()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name', 'users.email as user_email', 'users.role as user_role')
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                $session->last_activity_at = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                $session->is_current = $session->id === session()->getId();
                return $session;
            });

        return view('security.sessions', compact('sessions'));
    }

    /**
     * Force-destroy a session (force logout).
     */
    public function destroySession(string $id)
    {
        if ($id === session()->getId()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus sesi Anda sendiri.');
        }

        $session = DB::table('sessions')->where('id', $id)->first();

        DB::table('sessions')->where('id', $id)->delete();

        \App\Services\SecurityLogService::log(
            'session_destroyed',
            'Admin menghapus paksa sesi user.',
            'warning',
            auth()->id(),
            'session',
            $id,
            ['target_user_id' => $session->user_id ?? null]
        );

        return redirect()->route('security.sessions')->with('success', 'Sesi berhasil dihapus paksa.');
    }
}
