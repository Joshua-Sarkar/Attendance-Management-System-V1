# UI Debt Register
**Attendance Management System (AMS-V1)**

This register tracks remaining visual, layout, and usability debt identified in AMS-V1. It outlines severity, location, recommended resolutions, and the target phase for remediation (mostly aligned with the Phase 4.8 Executive Overhaul).

---

## Debt Register

### 1. Inconsistent Grid Wrappers & Spacing Containers
- **Description:** Page margins are inconsistent. Some views use standard Breeze responsive structures (`max-w-7xl mx-auto sm:px-6 lg:px-8`), while others use the dark-gold custom wrapper styling (`px-11 py-9 max-w-[1180px]`).
- **Location:** Across all `.blade.php` files in `resources/views/`.
- **Severity:** Medium (Creates inconsistent alignments on desktop screen widths).
- **Recommended Resolution:** Standardize all templates to use a single layout wrapper class and unified container widths.
- **Target Phase:** Phase 4.8

### 2. Multi-Column Workforce Directory Table Responsiveness
- **Description:** The Workforce directory table contains 10 columns (Employee ID, Name, Email, Role, Status, Department, Manager, Admin, Balance, Actions). On mobile devices, this forces horizontal scrolling that breaks visual grids.
- **Location:** `resources/views/employees/index.blade.php`
- **Severity:** Medium (Degrades usability on tablets and mobile screens).
- **Recommended Resolution:** Implement a responsive card-grid view for mobile layouts while preserving the structured table for desktop screens.
- **Target Phase:** Phase 4.8

### 3. Inline JavaScript Modals in Leaves Index
- **Description:** Leave approval, rejection, and override triggers are implemented using inline script blocks and raw absolute overlays. This deviates from our Alpine-based modal standard.
- **Location:** `resources/views/leaves/index.blade.php`
- **Severity:** Low (Maintains technical debt and compromises cleaner Blade/Alpine component reuse).
- **Recommended Resolution:** Refactor to use unified dynamic modal components.
- **Target Phase:** Phase 4.8

### 4. Raw Select Elements vs. Styled Component Dropdowns
- **Description:** Several forms use raw HTML `<select>` elements styled with raw CSS focus rings instead of custom blade select wrapper components.
- **Location:** `resources/views/leaves/create.blade.php`, `resources/views/dashboard.blade.php`, `resources/views/admin/correction-requests/index.blade.php`.
- **Severity:** Low (Creates slight visual divergence in select arrows across browsers).
- **Recommended Resolution:** Establish a canonical `<x-select-input>` component.
- **Target Phase:** Phase 4.8

### 5. Profile Details Section Length
- **Description:** The Employee Profile screen displays 10 structured sections stacked vertically. Users must scroll excessively to find details like bank accounts or education.
- **Location:** `resources/views/employees/show.blade.php`
- **Severity:** Medium (Increases cognitive load and scanning time).
- **Recommended Resolution:** Redesign the profile page using a tabbed sidebar or grouped horizontal columns (e.g. Identity, Personal, Professional, Financial).
- **Target Phase:** Phase 4.8

### 6. Legacy Dropdown Component (`<x-dropdown>`)
- **Description:** The settings menu in navigation dropdown uses standard Breeze styling with light/dark tailwind utilities (`bg-white dark:bg-gray-800`), which causes minor visual glitches when clicked in our dark gold theme context.
- **Location:** `resources/views/components/dropdown.blade.php`
- **Severity:** Low (Cosmetic color mismatch on click).
- **Recommended Resolution:** Replace with a dedicated dark-stone, gold-bordered dropdown layout.
- **Target Phase:** Phase 4.8

### 7. Form-to-Controller Redirect Reflows
- **Description:** Success and warning alerts are flashed to the session and render as banner boxes above tables. These boxes push down tables and form elements on page load, causing a slight visual reflow.
- **Location:** All main dashboard and index views.
- **Severity:** Low (Minor UX layout shift).
- **Recommended Resolution:** Implement absolute or toast-style floating alert components that do not disrupt the document layout flow.
- **Target Phase:** Phase 4.8
