<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'status',
        'duration',
        'reason',
        'borrowed_at',
        'due_date',
        'returned_at',
        'reject_reason',
        'reviewed_by',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'date',
        'returned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Cek apakah peminjaman sudah overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'Approved'
            && $this->returned_at === null
            && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Jumlah hari overdue (0 jika belum overdue).
     */
    public function overdueDays(): int
    {
        if (!$this->isOverdue()) return 0;
        return (int) now()->diffInDays($this->due_date, false);
    }

    /**
     * Scope: hanya yang overdue.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'Approved')
            ->whereNull('returned_at')
            ->where('due_date', '<', now());
    }

    /**
     * Scope: peminjaman aktif (approved & belum dikembalikan).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Approved')
            ->whereNull('returned_at');
    }
}
