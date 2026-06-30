# 11. Architecture Decisions & Design Principles

This document records the architectural decisions, structural patterns, and design rationales for the Attendance Management System. Future contributors must adhere to these design principles to ensure codebase cohesion.

---

## 1. Core Architectural Patterns

### Service-Oriented Architecture (SOA)
- **Decision**: All complex business calculations, status resolutions, and transactional database modifications must reside in dedicated Service classes (`app/Services/`) rather than inside Controllers or Eloquent Models.
- **Rationale**:
  - **Single Responsibility (SRP)**: Controllers should only validate incoming request data, enforce authorization checks, invoke the service layers, and route HTTP responses.
  - **Reusability**: Business calculations (like daily status calculations or leaf deductions) are needed across web controllers, Artisan console commands (e.g. `leaves:accrue`), and future API endpoints. Extracting logic to Services makes it accessible to all consumers.
  - **Testing**: Services can be unit-tested in isolation without mocking HTTP request and session context containers.

### Isolated Timing & Grace Calculations Engine (AttendanceTimingResolver)
- **Decision**: Timing operations, department shift timing offsets, grace boundaries, weekend resolutions, and half-day thresholds must be resolved through a single reusable timing component ([AttendanceTimingResolver](file:///c:/Users/Lenovo/AMS-V1/app/Services/AttendanceTimingResolver.php)).
- **Rationale**:
  - **Single Source of Truth**: Different layers (service check-ins, model accessors, and dashboard streak queries) previously computed timings independently. Centralizing calculations prevents logic drift (like check-in recording asserting 5-min grace while accessors asserted 15-min grace).
  - **Decoupling Models from Services**: Model accessors (like `Attendance@getLateMinutesAttribute`) must not directly call service engines to perform simple calculation calculations. Decoupling accessors from heavy services prevents circular dependency errors.

### Centralized Configuration Layer
- **Decision**: All operational constants, shift times, grace periods, weekly off days, leave accrual rates, and business policies (e.g. negative leave balances allowance) must be defined in the centralized configuration container ([config/attendance.php](file:///c:/Users/Lenovo/AMS-V1/config/attendance.php)).
- **Rationale**:
  - Future business-rule modifications must only require changing a single configuration parameter rather than hunting down and editing multiple controller validation rules, service layers, or model parameters.

### Model Encapsulation
- **Decision**: Eloquent Models (`app/Models/`) should encapsulate database column casts, entity relationships, and lightweight accessors. They must never contain complex query orchestrations or mutator writes that touch other models.
- **Rationale**: Keeps model classes clean, predictable, and simple. Intersubsystem dependencies must be managed in the service layer.

---

## 2. Rationales for Subsystem Decisions

### A. Why Attendance Timing Resolves Healthcare Dynamically
- **Context**: The Healthcare department requires unique timings: Shift starts at `10:00:00`, ends at `18:00:00`, and has `5` minutes grace.
- **Rationale**: 
  - To prevent database clutter or hardcoded checks in database tables, `AttendanceTimingResolver` checks if the department code/name matches `'healthcare'`. If it matches, it dynamically overrides parameters using the centralized configuration values.

### B. Why Attendance Overrides Sync with the Leave Ledger Transactionally
- **Context**: Overriding a daily attendance record to `'paid_leave'` or reverting it must keep the Leave Ledger and employee balances fully synchronized.
- **Rationale**:
  - **Atomicity**: Overrides are wrapped in database transactions using pessimistic lock rows (`User::lockForUpdate()`). If the operation is aborted (e.g., if negative balances are disabled and the employee has insufficient leave days), the transaction is fully rolled back, preventing balance discrepancies.
  - **Auditability**: Instead of simply changing the user's cached balance column, every override change creates a ledger row of type `'adjustment'`, providing a complete historical log.

### C. Why Workforce Import Circular Hierarchies are Verified in memory
- **Context**: Excel employee imports can introduce reporting loop cycles (e.g., A reports to B, B reports to A).
- **Rationale**:
  - Cycles are verified recursively in memory *before* writing any manager updates to the database. Checking all proposed links in memory ensures that if a cycle is detected, the entire batch transaction is aborted, preventing corrupt loop trees.

---

## 3. Principles for Future Contributors

1. **Keep Controllers Thin**: Controllers must never contain database queries or math algorithms. If a controller method exceeds 20 lines of database execution, extract it to a Service.
2. **Isolate Configuration from Logic**: Hardcoded magic numbers or timing strings are strictly forbidden. Timings, balance rates, and durations must be retrieved from configuration files (`config/`) or environment parameters (`.env`).
3. **Respect Relational Boundaries**: Do not perform manual queries that bypass Eloquent relationships. Use `$user->directReports` or `$user->department` mappings to keep relations traceable.
4. **Leverage Service Reusability**: Before writing a new helper method or service, verify if the calculation is already handled by `AttendanceTimingResolver` or relevant service layers.
