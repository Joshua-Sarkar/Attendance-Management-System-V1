<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequestLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'leave_request_id',
        'from_status',
        'to_status',
        'action',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
