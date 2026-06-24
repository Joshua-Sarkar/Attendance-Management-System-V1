<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveCredit extends Model
{
    protected $fillable = [
        'user_id',
        'credit_type',
        'amount',
        'used_amount',
        'status',
        'unlocked_at',
        'expires_at',
        'source_identifier',
        'granted_by',
        'notes',
        'source_metadata'
    ];

    protected $casts = [
        'unlocked_at' => 'date',
        'expires_at' => 'date',
        'source_metadata' => 'array',
        'amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'leave_credit_id');
    }
}
