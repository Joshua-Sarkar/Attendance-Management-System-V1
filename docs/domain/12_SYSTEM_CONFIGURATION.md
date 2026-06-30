# 12. System Configuration Map

This document serves as the directory for configurable operational parameters in the system, detailing default values, storage scopes, and files referencing each setting.

---

## 1. Timing & Grace Configuration

### A. Default Shift Start Time
- **Current Value**: `'09:30'`
- **Default Value**: `'09:30'`
- **Source**: Config file (`config/attendance.php` via env `ATTENDANCE_START_TIME`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)

### B. Default Shift End Time
- **Current Value**: `'18:30'`
- **Default Value**: `'18:30'`
- **Source**: Config file (`config/attendance.php` via env `ATTENDANCE_END_TIME`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)

### C. Default Grace Minutes
- **Current Value**: `15`
- **Default Value**: `15`
- **Source**: Config file (`config/attendance.php` via env `ATTENDANCE_GRACE_MINUTES`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)

### D. Healthcare Department Shift Timings Override
- **Shift Start**: `'10:00'`
- **Shift End**: `'18:00'`
- **Grace Minutes**: `5`
- **Source**: Config file (`config/attendance.php` via env parameters).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)

### E. New Rules Transition Date
- **Current Value**: Derived from env `ATTENDANCE_NEW_RULES_START_DATE`
- **Default Value**: `null` (historical fallback to `09:00` start / 15 grace)
- **Source**: Config file (`config/attendance.php` via env `ATTENDANCE_NEW_RULES_START_DATE`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)

---

## 2. Leave & Ledger Configuration

### A. Monthly Accrual Credit
- **Current Value**: `2.00` days
- **Default Value**: `2`
- **Source**: Config file (`config/attendance.php` via env `LEAVE_MONTHLY_ACCRUAL_RATE`).
- **Responsible Module**: Leave Request Management & Ledger.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Console/Commands/AccrueLeavesCommand.php](file:///c:/Users/Lenovo/AMS-V1/app/Console/Commands/AccrueLeavesCommand.php)

### B. Opening Balance Credit
- **Current Value**: `2.00` days
- **Default Value**: `2.00`
- **Source**: Code (Hardcoded).
- **Responsible Module**: Leave Request Management & Ledger.
- **Files Using Configuration**:
  - [app/Services/LeaveBalanceService.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/LeaveBalanceService.php)

### C. Birthday Leave Allocation Parameters
- **Unlock Window**: `1` day before birthday (configurable via `'attendance.birthday_leave_unlock_days'`).
- **Expiry Duration**: `1` year from unlock date (configurable via `'attendance.birthday_leave_expiry_years'`).
- **Credit Amount**: `1.00` day (Hardcoded).
- **Source**: Config file (`config/attendance.php`).
- **Responsible Module**: Birthday Leave.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Models/User.php](file:///c:/Users/Lenovo/AMS-V1/app/Models/User.php)

### D. Allow Negative Leave Balance Policy
- **Current Value**: `true`
- **Default Value**: `true`
- **Source**: Config file (`config/attendance.php` via env `LEAVE_ALLOW_NEGATIVE_BALANCE`).
- **Responsible Module**: Attendance Overrides & Leave Request Management.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Http/Controllers/AttendanceOverrideController.php](file:///c:/Users/Lenovo/AMS-V1/app/Http/Controllers/AttendanceOverrideController.php)

---

## 3. Security & Account Defaults

### A. Default Provisioning Password
- **Current Value**: Derived from env `DEFAULT_EMPLOYEE_PASSWORD`
- **Default Value**: `null` (Must be configured in env to pass startup assertions)
- **Source**: Config file (`config/employees.php` via env `DEFAULT_EMPLOYEE_PASSWORD`).
- **Responsible Module**: Authentication & Security.
- **Files Using Configuration**:
  - [config/employees.php](file:///c:/Users/Lenovo/AMS-V1/config/employees.php)
  - [app/Services/EmployeeImportService.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/EmployeeImportService.php)
  - [tests/Feature/PasswordStrategySecurityTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/PasswordStrategySecurityTest.php)

---

## 4. System Operational Thresholds

### A. Half-Day Working Hour Limit
- **Current Value**: `4.0` hours (configurable via `'attendance.half_day_threshold_hours'`).
- **Default Value**: `4.0`
- **Source**: Config file (`config/attendance.php`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)
  - [app/Services/AttendanceService.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceService.php)

### B. Weekly Off Exclusions
- **Current Value**: `'Sunday'` (configurable via `'attendance.weekly_off_day'`).
- **Default Value**: `'Sunday'`
- **Source**: Config file (`config/attendance.php`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceTimingResolver.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)
  - [app/Services/AttendanceService.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceService.php)
  - [app/Http/Controllers/AttendanceController.php](file:///c:/Users/Lenovo/AMS-V1/app/Http/Controllers/AttendanceController.php)

### C. Late Arrival Classification
- **Current Value**: `'half_day'` (configurable via `'attendance.late_arrival_classification'`).
- **Default Value**: `'half_day'`
- **Source**: Config file (`config/attendance.php`).
- **Responsible Module**: Attendance Tracking.
- **Files Using Configuration**:
  - [config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)
  - [app/Services/AttendanceService.php](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceService.php)

### D. Form Min Character Limits (e.g. Override reason)
- **Current Value**: `5` characters
- **Default Value**: `5`
- **Source**: Code (Validation rules).
- **Responsible Module**: Attendance Overrides, Leave Request Management.
- **Files Using Configuration**:
  - [app/Http/Controllers/AttendanceOverrideController.php](file:///c:/Users/Lenovo/AMS-V1/app/Http/Controllers/AttendanceOverrideController.php)
  - [app/Http/Controllers/LeaveRequestController.php](file:///c:/Users/Lenovo/AMS-V1/app/Http/Controllers/LeaveRequestController.php)

---

## 5. Related Modules & Cross References
- **[02_ATTENDANCE_RULES.md](file:///c:/Users/Lenovo/AMS-V1/docs/domain/02_ATTENDANCE_RULES.md)**: Resolves timing engine constants.
- **[03_LEAVE_RULES.md](file:///c:/Users/Lenovo/AMS-V1/docs/domain/03_LEAVE_RULES.md)**: Resolves birthday credit parameters.
