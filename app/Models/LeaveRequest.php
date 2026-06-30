<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'leave_credit_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approver_id',
        'approved_at',
        'rejection_reason',
        'notes',
        'is_paid',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'is_paid' => 'boolean',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function leaveCredit(): BelongsTo
    {
        return $this->belongsTo(LeaveCredit::class, 'leave_credit_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LeaveRequestLog::class);
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        $type = $this->leave_type;
        
        // Match complimentary/birthday credit first
        if (!$type && $this->leave_credit_id) {
            $type = 'complimentary';
        }

        // Birthday Leave (Paid)
        if ($type === 'complimentary' || $type === 'birthday_leave' || ($this->metadata && isset($this->metadata['is_birthday']) && $this->metadata['is_birthday'])) {
            return 'Birthday Leave (Paid)';
        }

        // Sick Leave
        if ($type === 'sick_leave' || $type === 'sick') {
            return 'Sick Leave';
        }

        // Emergency Leave
        if ($type === 'emergency_leave' || $type === 'emergency') {
            return 'Emergency Leave';
        }

        // Planned Leave (Paid or Unpaid)
        if ($type === 'planned' || $type === 'casual_leave' || $type === 'paid_leave') {
            return $this->is_paid ? 'Planned Leave (Paid)' : 'Planned Leave (Unpaid)';
        }

        // Unplanned Leave (Paid or Unpaid)
        if ($type === 'unplanned') {
            return $this->is_paid ? 'Unplanned Leave (Paid)' : 'Unplanned Leave (Unpaid)';
        }

        // Unpaid Leave legacy mappings
        if ($type === 'unpaid' || $type === 'unpaid_leave') {
            return 'Planned Leave (Unpaid)';
        }

        if ($type === 'work_from_home') {
            return 'Work From Home';
        }

        // Catch-all fallback for other unknown values:
        if (!empty($type)) {
            return ucwords(str_replace('_', ' ', $type));
        }

        // If type is completely null or empty, return default based on is_paid
        return $this->is_paid ? 'Planned Leave (Paid)' : 'Planned Leave (Unpaid)';
    }
}
