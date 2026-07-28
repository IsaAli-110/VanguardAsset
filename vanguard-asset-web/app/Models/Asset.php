<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    // Set primary key details for non-incrementing string IDs
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'type',
        'status',
        'purchase_cost',
        'purchase_date',
        'detail_json',
        'borrowed_by_id',
        'last_maintenance_date'
    ];

    protected $casts = [
        'detail_json' => 'array',
        'purchase_cost' => 'decimal:2',
        'purchase_date' => 'date',
        'last_maintenance_date' => 'date'
    ];

    /**
     * Relation to User currently borrowing the asset.
     */
    public function borrowedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrowed_by_id');
    }

    /**
     * Relation to approval requests associated with this asset.
     */
    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }
}
