<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceOverrideController extends Controller
{
    /**
     * Store an individual or bulk attendance override.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent,paid_leave,unpaid_leave,weekly_off,wfh,half_day',
            'classification' => 'nullable|string|in:full_day,half_day',
            'override_reason' => 'required|string|min:5',
            'user_id' => 'nullable|exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $date = Carbon::parse($validated['date'])->startOfDay();
        $status = $validated['status'];
        $classification = $validated['classification'] ?? null;

        if ($status === 'half_day') {
            $status = 'present';
            $classification = 'half_day';
        }

        $reason = $validated['override_reason'];
        
        $userIds = [];
        $overrideType = 'individual';

        if (!empty($validated['user_ids'])) {
            $userIds = $validated['user_ids'];
            $overrideType = count($userIds) > 1 ? 'bulk' : 'individual';
        } elseif (!empty($validated['user_id'])) {
            $userIds = [$validated['user_id']];
            $overrideType = 'individual';
        } else {
            return back()->withErrors(['user_ids' => 'You must select at least one employee.']);
        }

        $now = now();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($userIds, $date, $status, $classification, $reason, $overrideType, $request, $now) {
                foreach ($userIds as $userId) {
                    $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();

                    $attendance = Attendance::where('user_id', $userId)
                        ->where('date', $date)
                        ->lockForUpdate()
                        ->first();

                    if (!$attendance) {
                        $attendance = new Attendance([
                            'user_id' => $userId,
                            'date' => $date,
                        ]);
                    }

                    // 1. Calculate already deducted amount
                    $alreadyDeducted = 0.0;
                    if ($attendance->exists && $attendance->status === 'paid_leave') {
                        $alreadyDeducted = $attendance->classification === 'half_day' ? 0.5 : 1.0;
                    } else {
                        // Check if an approved leave request covers this day
                        $dateStr = $date->format('Y-m-d');
                        $hasLeave = \App\Models\LeaveRequest::where('user_id', $userId)
                            ->where('status', 'approved')
                            ->where('start_date', '<=', $dateStr . ' 23:59:59')
                            ->where('end_date', '>=', $dateStr . ' 00:00:00')
                            ->first();
                        if ($hasLeave && $hasLeave->leave_type !== 'complimentary' && $hasLeave->is_paid) {
                            $alreadyDeducted = 1.0; // standard leave request deducts 1 day per day
                        }
                    }

                    // Resolve target classification for deduction checking
                    $targetClassification = $classification;
                    if (empty($targetClassification)) {
                        $targetClassification = $attendance->exists ? ($attendance->automatic_classification ?? 'full_day') : 'full_day';
                    }

                    // 2. Calculate target deduction amount
                    $targetDeduction = 0.0;
                    if ($status === 'paid_leave') {
                        $targetDeduction = $targetClassification === 'half_day' ? 0.5 : 1.0;
                    }

                    $adjustment = $alreadyDeducted - $targetDeduction;

                    // 3. Negative Balance Policy check
                    if ($adjustment < 0.0) {
                        $allowNegative = (bool) config('attendance.allow_negative_leave_balance', true);
                        $netDeductionAmount = abs($adjustment);
                        if (!$allowNegative && ($user->leave_balance - $netDeductionAmount < 0.0)) {
                            throw new \Exception("Insufficient leave balance for {$user->name} ({$user->employee_id}). Balance is {$user->leave_balance} but this override requires deducting {$netDeductionAmount} leave day(s).");
                        }
                    }

                    // 4. Save Ledger Entry and update User balance if adjustment is non-zero
                    if ($adjustment != 0.0) {
                        $user->leave_balance += $adjustment;
                        $user->save();

                        \App\Models\LeaveLedgerEntry::create([
                            'user_id' => $userId,
                            'amount' => $adjustment,
                            'type' => 'adjustment',
                            'description' => "Adjustment due to attendance override on " . $date->format('Y-m-d') . " to status: {$status} (classification: {$targetClassification})",
                        ]);
                    }

                    // Preserve original computed values if not already overridden
                    if (!$attendance->exists) {
                        // Determine automatic values
                        $dateStr = $date->format('Y-m-d');
                        $leave = \App\Models\LeaveRequest::where('user_id', $userId)
                            ->where('status', 'approved')
                            ->where('start_date', '<=', $dateStr . ' 23:59:59')
                            ->where('end_date', '>=', $dateStr . ' 00:00:00')
                            ->first();
                        
                        $isWeeklyOff = \App\Services\AttendanceTimingResolver::isWeeklyOff($date);
                        $autoStatus = $leave ? ($leave->leave_type === 'work_from_home' ? 'wfh' : 'on_leave') : ($isWeeklyOff ? 'weekly_off' : 'absent');
                        $autoClassification = 'full_day';

                        $attendance->automatic_status = $autoStatus;
                        $attendance->automatic_classification = $autoClassification;
                        $attendance->automatic_classification_reason = null;
                    } else {
                        if (is_null($attendance->automatic_status)) {
                            $attendance->automatic_status = $attendance->status;
                        }
                        if (is_null($attendance->automatic_classification)) {
                            $attendance->automatic_classification = $attendance->classification;
                        }
                    }

                    $attendance->status = $status;
                    $attendance->classification = $targetClassification;
                    $attendance->is_overridden = true;
                    $attendance->overridden_by = $request->user()->id;
                    $attendance->overridden_at = $now;
                    $attendance->override_reason = $reason;
                    $attendance->override_type = $overrideType;
                    $attendance->save();
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['override_reason' => $e->getMessage()]);
        }

        return back()->with('success', 'Attendance override applied successfully.');
    }
}
