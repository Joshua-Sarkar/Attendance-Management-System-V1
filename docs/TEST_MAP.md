# AMS-V1 — Test Coverage Map

This document indexes all verification suites, automated test files, and assertions protecting the subsystems of AMS-V1 from regression.

---

## 1. Authentication & Security Testing

### Automated Test Files
* **[PasswordStrategySecurityTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/PasswordStrategySecurityTest.php)**
  * *Coverage Focus:* Validates onboarding security flows, default temporary password resets, and forced change redirections.
  * *Scenarios Verified:*
    1. Verifies that user creation fails if the `DEFAULT_EMPLOYEE_PASSWORD` env variable is missing.
    2. Verifies that an Admin can reset any employee's password back to default.
    3. Verifies that resetting a password re-arms `must_change_password` and forces the employee to complete the redirection flow on subsequent requests.
* **[AuthenticationTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/Auth/AuthenticationTest.php)**
  * *Coverage Focus:* Standard login controls.
  * *Scenarios Verified:*
    1. Users can authenticate using valid email and password credentials.
    2. Users cannot authenticate with invalid passwords.
* **[PasswordConfirmationTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/Auth/PasswordConfirmationTest.php)**
  * *Coverage Focus:* Confirms session passwords before accessing sensitive actions.
  * *Scenarios Verified:*
    1. Password confirmation screen renders.
    2. Active sessions are successfully confirmed.
* **[PasswordResetTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/Auth/PasswordResetTest.php)**
  * *Coverage Focus:* Forgot-password link creation and reset token redemption.
  * *Scenarios Verified:*
    1. Reset password link email can be requested.
    2. Users can set a new password using a valid email verification token.
* **[PasswordUpdateTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/Auth/PasswordUpdateTest.php)**
  * *Coverage Focus:* Standard profile password change form validation.
  * *Scenarios Verified:*
    1. Correct password must be supplied to update credentials.
    2. New password must match validation requirements.
* **[ExampleTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/ExampleTest.php)**
  * *Coverage Focus:* Root context redirects.
  * *Scenarios Verified:*
    1. Unauthenticated guest hits `/` and redirects to `/login`.
    2. Authenticated user hits `/` and redirects to `/dashboard`.

---

## 2. Department & Workforce Management Testing

### Automated Test Files
* **[HierarchySplitTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/HierarchySplitTest.php)**
  * *Coverage Focus:* Scopes user queries based on active session roles to verify department security and manager boundaries.
  * *Scenarios Verified:*
    1. **Department Access Control:** Checks that general staff can only view employees in their own department (if rules apply) and managers cannot perform operations outside direct reports.
    2. **Admin Creation Constraints:** Asserts that Admins can provision other Admins, but Managers cannot provision Admins or Managers.
    3. **Circular Mappings Block:** Verifies that a user cannot report to themselves, and Admins cannot be assigned a manager.
    4. **Assigned Employee Scopes:** Confirms that Managers are restricted to employees reporting directly to them and cannot view other managers' staff.
    5. **Unassigned Employees:** Verifies the visibility of employees with no manager.

---

## 3. Employee Profile & Encryption Testing

### Automated Test Files
* **[EmployeeProfileTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/EmployeeProfileTest.php)**
  * *Coverage Focus:* Validates profile relationships, lifecycle cascading, and transparent database encryption.
  * *Scenarios Verified:*
    1. **1:1 Bidirectional Relationships:** Verifies that a profile correctly links back to its User parent, and a User parent retrieves its profile attributes.
    2. **Cascading Deletions:** Asserts that when a User record is deleted from the workforce database, its corresponding `employee_profiles` row is automatically purged (database cascade).
    3. **Rest-Layer Encryption verification:** Saves sample Aadhaar, PAN, and Bank Account numbers, queries the database table directly to confirm the data is stored in encrypted ciphertext format, and checks that reading properties through model access returns decrypted plain text.
* **[EmployeeProfileAccessTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/EmployeeProfileAccessTest.php)**
  * *Coverage Focus:* Read boundaries and access scopes for HR profiles.
  * *Scenarios Verified:*
    1. Standard Employees can view their own profile tabs.
    2. Standard Employees are blocked (403 unauthorized) from viewing other employees' profiles.
    3. Admins can view any employee profile record.
    4. Managers can view their direct reports' profiles but are blocked from viewing profiles of employees reporting to other managers.

---

## 4. Attendance Tracking & Auditing Testing

### Automated Test Files
* **[AttendanceVerificationTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/AttendanceVerificationTest.php)**
  * *Coverage Focus:* Validates core employee clock-in and clock-out controller endpoints.
  * *Scenarios Verified:*
    1. Employees can check in successfully and have a database record created with active timestamps.
    2. Employees can check out, which updates the same record with the end timestamp.
    3. Prevents duplicate clock-ins by returning error validations if a record already exists for the day.
* **[AttendanceMetricsTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/AttendanceMetricsTest.php)**
  * *Coverage Focus:* Verifies shift start rules, grace thresholds, and late minutes math.
  * *Scenarios Verified:*
    1. Checks status as `present` if clock-in is on or before the grace end (e.g. 09:15 under old rules, or 09:45 under new rules).
    2. Checks status as `late` if clock-in is after the grace end (e.g. at or after 09:16).
    3. Asserts the correct late arrival minute calculations (e.g., checking in at 09:30 AM logs exactly 15 late minutes under a 09:00 AM shift start with a 15-minute grace period).
* **[AttendanceAuditTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/AttendanceAuditTest.php)**
  * *Coverage Focus:* HR search filters and exception aggregates inside the Punctuality Audit console.
  * *Scenarios Verified:*
    1. HR can query attendance records by date, name, status, or department.
    2. The audit center calculates the average delay minutes correctly.
    3. Restricted visibility prevents unauthorized roles from querying global audit tables.
* **[WorkingDaysTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/WorkingDaysTest.php)**
  * *Coverage Focus:* Weekend definitions.
  * *Scenarios Verified:*
    1. Verifies that Saturdays are treated as standard working days.
    2. Verifies that Sundays are flagged as weekends and are excluded from absence calculation reports.

---

## 5. Leave Request Management Testing

### Automated Test Files
* **[LeaveManagementTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/LeaveManagementTest.php)**
  * *Coverage Focus:* Validates employee leave submissions, validation gates, manager approvals, cancellations, and attendance overrides.
  * *Scenarios Verified:*
    1. **Employee Submission:** Confirms employees can apply for leaves with nullable types, recording them as pending.
    2. **Manager Approval Boundaries:** Checks that assigned managers can approve requests as paid/unpaid or reject them, while non-assigned managers are blocked (403 unauthorized).
    3. **Circular Approval Prevention:** Verifies managers cannot approve their own leave requests; manager requests must be reviewed by Admins.
    4. **Admin Auto-Approvals:** Confirms Admin self-submissions are automatically approved but require selecting Paid/Unpaid on creation.
    5. **Date Constraint Validations:** Blocks submissions where start date is in the past, or end date is before start date.
    6. **Overlaps Block:** Asserts that overlapping leave request dates are rejected during validation, but cancelled requests do not block future bookings.
    7. **Cancellation Actions:** Verifies employees can cancel their own pending or approved requests.
    8. **Attendance Sync (Rule B):** Confirms approved leaves automatically override physical dashboard status as `on_leave` or `wfh` if no check-in exists.
    9. **Physical Override:** Confirms a physical dashboard clock-in event overrides active leave requests, setting status back to `present` or `late`.
* **[LeaveAuthorizationModelTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/LeaveAuthorizationModelTest.php)**
  * *Coverage Focus:* Validates planned and unplanned leave approvals/rejections, dynamic birthday leave credits lifecycle (syncing, unlocking, expiration), auto-approvals, leap year birthday edge cases, and admin override corrections.
  * *Scenarios Verified:*
    1. **Planned Leave Approval:** Asserts that approving planned leaves deducts standard balances and dynamically resolves attendance to `on_leave`.
    2. **Planned Leave Rejection:** Asserts that rejecting planned leaves does not deduct balance and defaults attendance status to `absent` (salary-deducted) when no clock-in exists.
    3. **Birthday Credit Sync & Expiry:** Verifies birthday credits are dynamically synced/unlocked exactly 1 day before the birthday, stay active, and expire 12 months after the birthday.
    4. **Birthday Leave Auto-Approval:** Confirms employee birthday leave applications are auto-approved and consume the synced complimentary leave credit token while leaving the regular leave balance untouched.
    5. **Birthday Override Restore:** Asserts that an Admin overriding and rejecting an approved birthday leave restores the complimentary leave credit used and sets attendance status back to `absent`.
    6. **Physical Clock-in Override:** Verifies that physical check-in events override approved leave requests, resolving attendance to `present` or `late`.
    7. **Leap Year Support:** Validates that employees born on February 29 have their birthday credits successfully unlocked on February 27 in non-leap years.

---

## 6. Leave Balance Ledger & Accruals Testing

### Automated Test Files
* **[LeaveBalanceTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/LeaveBalanceTest.php)**
  * *Coverage Focus:* Transaction safety, opening balance initialization, monthly accruals, refunds, and administrative adjustments.
  * *Scenarios Verified:*
    1. **Automatic Profile Initialization:** Asserts that manual creation of employees initializes a default credit of 2.00 in the ledger and users table.
    2. **Opening Balance Idempotency:** Verifies the `leaves:initialize-balances` console command backfills opening credits, and double execution does not add duplicate records.
    3. **Monthly Accrual credits:** Validates the `leaves:accrue` cron command adds 2.00 credits to all active employees and logs matching ledger lines.
    4. **Accrual Idempotency:** Checks that running the accrual command multiple times in the same calendar month is blocked (idempotent guard).
    5. **Deductions:** Checks that paid leave approvals deduct days from balance and log deduction records, while unpaid leave approvals bypass balance modifications.
    6. **Refunds:** Verifies that cancelling an approved paid leave triggers a credit refund and records a refund ledger row.
    7. **Admin Overrides Balance correction:** Checks all transitions in the override matrix (e.g. changing an approved paid request to unpaid/rejected refunds the days; changing unpaid to paid checks and deducts balance).

---

## 7. Zimyo Excel Import Engine Testing

### Automated Test Files
* **[ImportEmployeesTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/ImportEmployeesTest.php)**
  * *Coverage Focus:* Validates uploader role authorization, file parsing accuracy, mapping chains resolution, and warning summaries logic.
  * *Scenarios Verified:*
    1. **Uploader Authorization:** Confirms HR Admins can access uploader view and trigger sheet processing posts, while general Managers/Employees are blocked (403 unauthorized).
    2. **Roster Creation & Profile updates:** Parses a mock xlsx file containing employee details and asserts that User accounts and profiles are successfully registered with matching parameters.
    3. **Two-Pass Hierarchy validation:** Verifies that employee records are correctly mapped to their reporting manager's user primary key in Pass 2, even if the manager is created after the subordinate during file parsing.
    4. **Warning Summaries Logging:** Processes rows with missing email addresses, invalid employee statuses, or unmapped departments, verifying that the uploader skips the target rows, records warnings, and logs JSON outputs to the `import_logs` table.

---

## 8. Profile Correction Requests Testing

### Automated Test Files
* **[ProfileCorrectionRequestTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/ProfileCorrectionRequestTest.php)**
  * *Coverage Focus:* Validates employee request submissions, duplicate blocks, queue visibility, and resolution updates.
  * *Scenarios Verified:*
    1. **Employee Submission:** Confirms that standard employees can submit correction requests specifying fields (e.g. `bank_name`).
    2. **Duplicate submission guard:** Blocks employees from submitting a new request if they already have an active request in `pending` state.
    3. **Admin Queue Visibility:** Verifies HR Admins can view the central index of requests, while standard employees are blocked (403 unauthorized).
    4. **Resolution audits:** Checks that Admins can resolve pending requests, which sets the `resolved_by` and `resolved_at` columns, and updates request status to `resolved`.

---

## 9. Deployment & Infrastructure Operations Testing

### Automated Test Files
* **[TimezoneTest.php](file:///c:/Users/Lenovo/AMS-V1/tests/Feature/TimezoneTest.php)**
  * *Coverage Focus:* Confirms active environment timezone settings match corporate requirements.
  * *Scenarios Verified:*
    1. **Asia/Kolkata Locking:** Asserts that the application's configuration timezone maps strictly to `Asia/Kolkata` (IST - UTC+05:30) to prevent date offsets or incorrect delays math on production environments.
