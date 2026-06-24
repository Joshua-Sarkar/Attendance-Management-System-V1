# UI Component Inventory
**Attendance Management System (AMS-V1)**

This inventory documents all active UI components, their purpose, codebase locations, consistency score, accessibility status, and recommended action as we prepare for Phase 4.8.

---

## Component Audit

### 1. Primary Button (`<x-primary-button>`)
- **Purpose:** Primary positive actions (e.g. Save, Submit, Confirm, Filter).
- **Locations Used:**
  - `resources/views/components/primary-button.blade.php`
  - Used in edit employee profile form, modal submittals, correction request dialogs.
- **Current State:** Dark gold background (`bg-brass text-canvas`), proper uppercase tracking.
- **Design Consistency Score:** 9/10
- **Accessibility Status:** Normal text meets 4.5:1 ratio against dark background. Focus ring offset present.
- **Recommendation:** **KEEP**

### 2. Secondary Button (`<x-secondary-button>`)
- **Purpose:** Neutral cancel actions and secondary filters.
- **Locations Used:**
  - `resources/views/components/secondary-button.blade.php`
  - Used in modals, cancel forms, and filter resets.
- **Current State:** Bordered stone styling (`bg-surface-raised border-hairline text-vellum`).
- **Design Consistency Score:** 8/10
- **Accessibility Status:** Good legibility.
- **Recommendation:** **KEEP**

### 3. Danger Button (`<x-danger-button>`)
- **Purpose:** Destructive/negative actions (e.g. Delete, Cancel Leave).
- **Locations Used:**
  - `resources/views/components/danger-button.blade.php`
  - Inline forms (e.g. delete employee).
- **Current State:** Burgundy background (`bg-burgundy text-vellum border-burgundy/30`).
- **Design Consistency Score:** 9/10
- **Accessibility Status:** High contrast, legible.
- **Recommendation:** **KEEP**

### 4. Text Input (`<x-text-input>`)
- **Purpose:** Standard single-line text and date entries.
- **Locations Used:**
  - `resources/views/components/text-input.blade.php`
  - Used in edit employee profile, leave app forms, correction details.
- **Current State:** Dark stone background (`bg-surface-raised`), thin gold hairline focus outline.
- **Design Consistency Score:** 8/10
- **Accessibility Status:** Clear outline states; text color `#ECE4D3` has sufficient contrast.
- **Recommendation:** **KEEP**

### 5. Input Label (`<x-input-label>`)
- **Purpose:** Form text descriptors.
- **Locations Used:**
  - `resources/views/components/input-label.blade.php`
  - Used alongside inputs.
- **Current State:** Simple vellum-muted labels.
- **Design Consistency Score:** 9/10
- **Accessibility Status:** Standard text contrast compliant.
- **Recommendation:** **KEEP**

### 6. Input Error (`<x-input-error>`)
- **Purpose:** Render Laravel validation failure text.
- **Locations Used:**
  - `resources/views/components/input-error.blade.php`
- **Current State:** Soft red/burgundy alert text.
- **Design Consistency Score:** 9/10
- **Accessibility Status:** High legibility red text.
- **Recommendation:** **KEEP**

### 7. Core Sidebar (`<x-sidebar>`)
- **Purpose:** Main application side navigation pane.
- **Locations Used:**
  - `resources/views/components/sidebar.blade.php` (called in `layouts/app.blade.php`).
- **Current State:** Left column styling with active route indicators, company crest, and user logout metadata.
- **Design Consistency Score:** 8/10
- **Accessibility Status:** SVGs and text have solid contrast. Target click heights are >= 44px.
- **Recommendation:** **KEEP**

### 8. Dropdown Menu (`<x-dropdown>`)
- **Purpose:** Collapsible option selections.
- **Locations Used:**
  - `resources/views/components/dropdown.blade.php`, `dropdown-link.blade.php`
- **Current State:** Breeze light/dark hybrid styling using `bg-white dark:bg-gray-800`.
- **Design Consistency Score:** 4/10 (Conflicts with dark corporate surface design).
- **Accessibility Status:** Low contrast menu items on click.
- **Recommendation:** **REBUILD** (Incorporate custom dark stone styling).

### 9. Modal Container (`<x-modal>`)
- **Purpose:** Modal dialog frames (Alpine-driven).
- **Locations Used:**
  - `resources/views/components/modal.blade.php`
  - Profile correction modal.
- **Current State:** Black backdrop, rounded container panel using `glass-panel`.
- **Design Consistency Score:** 7/10
- **Accessibility Status:** Traps keyboard focus correctly; esc key functional.
- **Recommendation:** **KEEP**

### 10. Legacy Custom Modals (Vanilla JS)
- **Purpose:** Popup approval/rejection decisions in Leaves Index.
- **Locations Used:**
  - Inline in `resources/views/leaves/index.blade.php`.
- **Current State:** Handcrafted absolute overlays with inline Javascript target toggles.
- **Design Consistency Score:** 3/10 (Different from `<x-modal>`, contains hardcoded style overrides).
- **Accessibility Status:** Dark text on dark background on submit button (`bg-forest` + `text-canvas` inside a dark modal).
- **Recommendation:** **REBUILD** (Unify into a single, clean Blade modal component in Phase 4.8).

### 11. Status Badge (`.tag` utility)
- **Purpose:** Visual indicators for attendance states (Present, Late, Absent, WFH, Leave, Weekend).
- **Locations Used:**
  - `resources/css/app.css`
  - Rendered inline across dashboards, logs, and directories.
- **Current State:** Pill-shaped tags with desaturated background overlays.
- **Design Consistency Score:** 5/10 (Currently using colors that are low contrast, e.g. dark text on dark backgrounds in list views).
- **Accessibility Status:** WCAG Failures (contrast ratios ranging from 1.1:1 to 3.1:1).
- **Recommendation:** **REBUILD** (Remediate background/text colors in Phase 4.7.3 CSS to guarantee WCAG compliance).

### 12. Ledger Seal (`.seal` utility)
- **Purpose:** Timeline activity markers.
- **Locations Used:**
  - `resources/css/app.css`
  - Rendered in Today's Ledger timelines.
- **Current State:** Circle dots aligned with vertical rule indicators.
- **Design Consistency Score:** 8/10
- **Accessibility Status:** Clear status colors, but lacks tooltips/text alternatives for screen readers.
- **Recommendation:** **KEEP** (Ensure text alternative exists on the associated row in Phase 4.7.3).

### 13. Stat Card (`.stat-card`)
- **Purpose:** Dashboard metric summaries.
- **Locations Used:**
  - `resources/css/app.css`
  - Main Dashboard, Employee Dashboard, My Attendance.
- **Current State:** Hover translation effect and colored top indicators.
- **Design Consistency Score:** 7/10 (Some dashboard stats cards use custom inline variations instead of the `.stat-card` class).
- **Accessibility Status:** Numerical labels are readable, but label texts can have low contrast.
- **Recommendation:** **KEEP** (Harmonize values and labels in Phase 4.7.3).

### 14. Information Panel (`.panel`)
- **Purpose:** Main content cards and layout blocks.
- **Locations Used:**
  - `resources/css/app.css`
  - Encloses tables, grids, and filters across views.
- **Current State:** Rounded borders with subtle gold hairlines (`border-hairline`).
- **Design Consistency Score:** 9/10
- **Accessibility Status:** Compliant background-to-border contrast.
- **Recommendation:** **KEEP**

### 15. Employee Creator Layout (`employees/create.blade.php`)
- **Purpose:** HR form to add new employees.
- **Locations Used:**
  - `resources/views/employees/create.blade.php`
- **Current State:** Legacy Breeze white backgrounds and indigo focus highlights.
- **Design Consistency Score:** 2/10 (Severely inconsistent with the dark theme).
- **Accessibility Status:** Unusable in dark/gold palette context.
- **Recommendation:** **REBUILD** (Re-skin completely to match corporate dark-gold styling in Phase 4.7.3).

### 16. Profile Editor Layout (`employees/edit.blade.php`)
- **Purpose:** Update employee records.
- **Locations Used:**
  - `resources/views/employees/edit.blade.php`
- **Current State:** Standard glass-panel layout using dark-gold input fields.
- **Design Consistency Score:** 8/10
- **Accessibility Status:** Good legibility.
- **Recommendation:** **KEEP**

### 17. User Profile Controller Form (`profile/edit.blade.php`)
- **Purpose:** Employee password resets and details updates.
- **Locations Used:**
  - `resources/views/profile/edit.blade.php` and partials folder.
- **Current State:** Default Breeze light-mode templates with standard gray lines.
- **Design Consistency Score:** 2/10 (Severe color mismatches).
- **Accessibility Status:** Inoperable contrast ratios.
- **Recommendation:** **REBUILD** (Re-skin layouts and child inputs in Phase 4.7.3).

### 18. Attendance History Stats (`attendance/history.blade.php`)
- **Purpose:** Visual overview of personal attendance logs.
- **Locations Used:**
  - `resources/views/attendance/history.blade.php`
- **Current State:** Glass-panels with bright error-pink, purple border outlines, and neon ticks.
- **Design Consistency Score:** 3/10 (Completely breaks visual hierarchy).
- **Accessibility Status:** Extremely poor contrast ratios on status chips.
- **Recommendation:** **REBUILD** (Replace with corporate stat cards and desaturated tag pills in Phase 4.7.3).

---

## Classification Summary

- **KEEP (Preserve & Clean):**
  - `<x-primary-button>`, `<x-secondary-button>`, `<x-danger-button>`, `<x-text-input>`, `<x-input-label>`, `<x-input-error>`, `<x-sidebar>`, `<x-modal>`, `.seal` marker, `.panel` containers, `.stat-card` elements, `employees/edit.blade.php`.
- **REBUILD (Execute in Phase 4.7.3):**
  - **Status Badge (`.tag`):** Remediate contrast colors in CSS.
  - **Employee Creator (`employees/create.blade.php`):** Convert white styling to dark stone panels.
  - **Profile Editor (`profile/edit.blade.php` & partials):** Re-skin inputs and containers.
  - **Attendance History (`attendance/history.blade.php`):** Align stats widgets and colors.
- **REBUILD/DEPRECATE (Defer to Phase 4.8):**
  - **Legacy Dropdown (`<x-dropdown>`):** Deprecate Breeze structure and create a custom dark-gold widget.
  - **Inline JavaScript Modals (Leaves Index):** Migrate to a unified modal architecture.
