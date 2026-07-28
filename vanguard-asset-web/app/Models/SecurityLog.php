<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'severity',
        'ip_address',
        'user_agent',
        'target_type',
        'target_id',
        'metadata',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Relation to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by event type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope: filter by severity.
     */
    public function scopeSeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Get a human-readable label for the event.
     */
    public function getEventLabelAttribute(): string
    {
        return match($this->event_type) {
            'login_success'    => 'Login Berhasil',
            'login_failed'     => 'Login Gagal',
            'logout'           => 'Logout',
            'account_locked'   => 'Akun Dikunci',
            'asset_created'    => 'Aset Dibuat',
            'asset_updated'    => 'Aset Diupdate',
            'asset_deleted'    => 'Aset Dihapus',
            'approval_approved'=> 'Peminjaman Disetujui',
            'approval_rejected'=> 'Peminjaman Ditolak',
            'asset_returned'   => 'Aset Dikembalikan',
            'role_changed'     => 'Role Diubah',
            'session_destroyed'=> 'Sesi Dihapus Paksa',
            'depreciation_calc'=> 'Kalkulasi Depresiasi',
            default            => $this->event_type,
        };
    }

    /**
     * Get severity color for UI badge.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'danger',
            'warning'  => 'warning',
            default    => 'info',
        };
    }
}
