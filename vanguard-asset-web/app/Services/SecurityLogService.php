<?php

namespace App\Services;

use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityLogService
{
    /**
     * Record a security event.
     */
    public static function log(
        string $eventType,
        string $description,
        string $severity = 'info',
        ?int $userId = null,
        ?string $targetType = null,
        ?string $targetId = null,
        ?array $metadata = null,
        ?Request $request = null
    ): SecurityLog {
        $req = $request ?? request();

        return SecurityLog::create([
            'user_id'     => $userId ?? auth()->id(),
            'event_type'  => $eventType,
            'severity'    => $severity,
            'ip_address'  => $req->ip(),
            'user_agent'  => $req->userAgent(),
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'metadata'    => $metadata,
            'description' => $description,
        ]);
    }

    /**
     * Log a successful login.
     */
    public static function logLogin(int $userId, Request $request): void
    {
        self::log(
            'login_success',
            'User berhasil login.',
            'info',
            $userId,
            'App\\Models\\User',
            (string) $userId,
            null,
            $request
        );
    }

    /**
     * Log a failed login attempt.
     */
    public static function logFailedLogin(string $email, Request $request): void
    {
        self::log(
            'login_failed',
            "Percobaan login gagal untuk email: {$email}",
            'warning',
            null,
            null,
            null,
            ['email' => $email],
            $request
        );
    }

    /**
     * Log account lockout event.
     */
    public static function logAccountLocked(string $email, Request $request): void
    {
        self::log(
            'account_locked',
            "Akun {$email} dikunci karena terlalu banyak percobaan login gagal.",
            'critical',
            null,
            null,
            null,
            ['email' => $email],
            $request
        );
    }

    /**
     * Log logout event.
     */
    public static function logLogout(int $userId, Request $request): void
    {
        self::log('logout', 'User logout.', 'info', $userId, 'App\\Models\\User', (string) $userId, null, $request);
    }

    /**
     * Log asset CRUD operations.
     */
    public static function logAssetEvent(string $action, string $assetId, string $assetName, ?int $userId = null): void
    {
        $labels = [
            'created' => ['asset_created', "Aset '{$assetName}' (ID: {$assetId}) dibuat.", 'info'],
            'updated' => ['asset_updated', "Aset '{$assetName}' (ID: {$assetId}) diupdate.", 'info'],
            'deleted' => ['asset_deleted', "Aset '{$assetName}' (ID: {$assetId}) dihapus.", 'warning'],
        ];

        $info = $labels[$action] ?? ['asset_event', "Aksi '{$action}' pada aset {$assetId}.", 'info'];

        self::log($info[0], $info[1], $info[2], $userId, 'App\\Models\\Asset', $assetId);
    }

    /**
     * Log approval actions.
     */
    public static function logApprovalEvent(string $action, int $requestId, string $assetName, ?int $userId = null): void
    {
        $labels = [
            'approved' => ['approval_approved', "Peminjaman aset '{$assetName}' disetujui.", 'info'],
            'rejected' => ['approval_rejected', "Peminjaman aset '{$assetName}' ditolak.", 'info'],
            'returned' => ['asset_returned', "Aset '{$assetName}' dikembalikan.", 'info'],
        ];

        $info = $labels[$action] ?? ['approval_event', "Aksi '{$action}' pada peminjaman #{$requestId}.", 'info'];

        self::log($info[0], $info[1], $info[2], $userId, 'App\\Models\\ApprovalRequest', (string) $requestId);
    }

    /**
     * Count failed login attempts from an IP in the last N minutes.
     */
    public static function countRecentFailedLogins(string $ipAddress, int $minutes = 15): int
    {
        return SecurityLog::where('event_type', 'login_failed')
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Check if an IP is currently locked out.
     */
    public static function isLockedOut(string $ipAddress, int $maxAttempts = 5, int $lockoutMinutes = 15): bool
    {
        return self::countRecentFailedLogins($ipAddress, $lockoutMinutes) >= $maxAttempts;
    }

    /**
     * Get lockout remaining seconds.
     */
    public static function getLockoutRemainingSeconds(string $ipAddress, int $maxAttempts = 5, int $lockoutMinutes = 15): int
    {
        $lastFailed = SecurityLog::where('event_type', 'login_failed')
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subMinutes($lockoutMinutes))
            ->latest()
            ->first();

        if (!$lastFailed || self::countRecentFailedLogins($ipAddress, $lockoutMinutes) < $maxAttempts) {
            return 0;
        }

        $lockoutEnds = $lastFailed->created_at->addMinutes($lockoutMinutes);
        return max(0, now()->diffInSeconds($lockoutEnds, false));
    }
}
