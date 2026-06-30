<?php

return [
    'start_time' => env('ATTENDANCE_START_TIME', '09:30'),
    'end_time' => env('ATTENDANCE_END_TIME', '18:30'),
    'grace_minutes' => (int) env('ATTENDANCE_GRACE_MINUTES', 15),
    'leave_annual_allocation' => (int) env('LEAVE_ANNUAL_ALLOCATION', 24),
    'leave_monthly_accrual_rate' => (int) env('LEAVE_MONTHLY_ACCRUAL_RATE', 2),
    'new_rules_start_date' => env('ATTENDANCE_NEW_RULES_START_DATE'),

    // Centralized Business Rules Constants
    'half_day_threshold_hours' => (float) env('ATTENDANCE_HALF_DAY_THRESHOLD_HOURS', 4.0),
    'weekly_off_day' => env('ATTENDANCE_WEEKLY_OFF_DAY', 'Sunday'),
    'birthday_leave_unlock_days' => (int) env('BIRTHDAY_LEAVE_UNLOCK_DAYS', 1),
    'birthday_leave_expiry_years' => (int) env('BIRTHDAY_LEAVE_EXPIRY_YEARS', 1),
    'allow_negative_leave_balance' => (bool) env('LEAVE_ALLOW_NEGATIVE_BALANCE', true),
    'late_arrival_classification' => env('ATTENDANCE_LATE_ARRIVAL_CLASSIFICATION', 'half_day'),

    // Specific Department Default Overrides
    'departments' => [
        'healthcare' => [
            'start_time' => env('HEALTHCARE_SHIFT_START', '10:00'),
            'end_time' => env('HEALTHCARE_SHIFT_END', '18:00'),
            'grace_minutes' => (int) env('HEALTHCARE_GRACE_MINUTES', 5),
        ],
    ],
];
