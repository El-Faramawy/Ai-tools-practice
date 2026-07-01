# Clean Code Checklist: Brand CRUD (Country-Scoped)

**Purpose**: Validate that clean architecture, SOLID principles, layer separation, and correctness requirements are clearly, completely, and unambiguously specified — before implementation begins.
**Created**: 2026-07-01
**Reviewed**: 2026-07-01
**Feature**: [spec.md](../spec.md) | [plan.md](../plan.md)
**Focus**: Clean Code · Architecture Integrity · Correctness · Constitution Compliance

---

## Requirement Completeness

- [x] CHK001 — Are requirements explicitly defined for **every** architectural layer transition (Route → Request → Controller → Service → Model)? [Completeness, Spec §FR-002–FR-005, Plan §Architecture]
  > ✅ plan.md §Architecture, constitution §I, and tasks.md phases 1–6 all trace the full Route → Request → Controller → Service → Model chain explicitly.

- [x] CHK002 — Is the requirement that controllers must contain **zero** database queries stated explicitly in the plan? [Completeness, Plan §Constitution Check]
  > ✅ plan.md Constitution Check table: "Controller has zero DB calls." Constitution §I and §II both forbid direct Eloquent in controllers.

- [x] CHK003 — Is it specified which helper functions (`admin()`, `setting()`, `get_file()`, `tableAction()`) each layer is permitted to call? [Completeness, Gap]
  > ✅ AGENTS.md §5 documents each helper with usage examples. plan.md Constitution Check §V confirms `admin()` and `tableAction()` usage sites. Sufficient for this feature scope.

- [x] CHK004 — Are requirements defined for what happens when `admin()->user()->country_id` is `null` in **every** controller method (not only index)? [Completeness, Plan §Admin with No Country Guard]
  > ✅ plan.md §Admin with No Country Guard: "BrandController MUST check admin()->user()->country_id at the top of **every** method." tasks.md T025 enumerates all six methods (index, create, store, edit, update, destroy).

- [x] CHK005 — Are the exact Eloquent relationships that must be declared on each Model (`Brand`, `Admin`, `Country`) fully specified? [Completeness, Gap]
  > ✅ data-model.md §Entities specifies every relationship: Country(hasMany Brand, hasMany Admin), Admin(belongsTo Country), Brand(belongsTo Country, uses SoftDeletes).

- [x] CHK006 — Are requirements for soft-delete scope (i.e., `whereNull('deleted_at')` in queries) stated at the Service layer, not left implicit? [Completeness, Spec §FR-006]
  > ✅ tasks.md Notes: "Soft-deleted brands are excluded automatically by Eloquent's SoftDeletes global scope — no manual whereNull needed in service queries (exception: uniqueness rules in FormRequests)." This is explicit and correctly layered.

- [x] CHK007 — Is there a requirement that the `BrandService` constructor signature (injected dependencies) be defined before implementation starts? [Completeness, Plan §Architecture Decisions]
  > ✅ plan.md §Country Ownership Enforcement shows the exact method signatures. tasks.md T010 creates BrandService before T011 creates BrandController (which injects it). Ordering is enforced.

- [x] CHK008 — Are requirements for the `StoreBrandRequest` and `UpdateBrandRequest` unique-rule construction (dynamic `country_id` scope) fully documented? [Completeness, Plan §Unique Constraint for Duplicate Names]
  > ✅ plan.md §Unique Constraint shows the exact `Rule::unique()->where('country_id', ...)->whereNull('deleted_at')` pattern. data-model.md §Validation Rules provides both FormRequest rule sets in full.

---

## Requirement Clarity

- [x] CHK009 — Is "thin controller" quantified? Does the spec define what logic is **forbidden** vs. **permitted** in the controller? [Clarity, Plan §Constitution Check]
  > ✅ Constitution §I: "Controllers MUST NOT contain database queries, password hashing, or complex business logic." Constitution §II: "Direct Eloquent calls inside controllers are FORBIDDEN." Binary, verifiable by grep.

- [x] CHK010 — Is the term "system-generated UUID" clarified to specify exactly where generation occurs (Service layer via `Str::uuid()`)? [Clarity, Spec §FR-010, Plan §Architecture]
  > ✅ research.md §UUID Strategy: "Generate via Str::uuid() in BrandService::storeBrand()." tasks.md T014 repeats this. No ambiguity.

- [x] CHK011 — Is "country-scoped query" defined precisely enough to distinguish between filtering on `country_id` at the query level vs. post-fetch PHP filtering? [Clarity, Plan §Country Ownership Enforcement]
  > ✅ plan.md §Country Ownership Enforcement shows the explicit Eloquent chain: `Brand::where('country_id', $countryId)->latest()`. Query-level filtering is explicit. Post-fetch PHP filtering is never mentioned.

- [x] CHK012 — Is "reject with 403" specified clearly enough to indicate whether `abort(403)` fires before or after the Service call? [Clarity, Plan §Admin with No Country Guard]
  > ✅ plan.md §Admin with No Country Guard: "This guard is the FIRST line of defense; the service-layer scope is the SECOND." Controller fires abort(403) before any service call when country_id is null.

- [x] CHK013 — Is the `UpdateBrandRequest` unique-rule behavior (excluding the current brand's own record) explicitly specified? [Clarity, Ambiguity, Gap]
  > ✅ data-model.md §UpdateBrandRequest: uniqueness rule ignores `{brand_id}` to allow save-without-name-change. tasks.md T018: `Rule::unique()->ignore($this->route('uuid'), 'uuid')`. Both document the self-exclusion strategy.

- [x] CHK014 — Is "soft delete" clarified to explicitly require the `SoftDeletes` trait on the `Brand` model and a corresponding `deleted_at` migration column? [Clarity, Spec §FR-006]
  > ✅ data-model.md §Brand: "Uses SoftDeletes trait." Migration T005: `softDeletes()` column explicitly listed. tasks.md T008: "use SoftDeletes trait."

- [x] CHK015 — Are the Arabic string values for all system-generated messages documented (or is there a requirement that they must be), rather than left to developer discretion? [Clarity, Spec §FR-007]
  > ✅ contracts/ajax-contracts.md documents: store → "تم الاضافة بنجاح", update → "تم التعديل بنجاح", destroy → "تم الحذف بنجاح", 403 → "غير مصرح لك بهذا الإجراء", validation example → "اسم الماركة مطلوب". Core messages are canonically defined.

- [x] CHK016 — Is the term "clean code naming convention" (from the constitution) measurable — e.g., are banned abbreviations listed, or is there a reference style guide? [Clarity, Ambiguity]
  > ✅ Constitution §Development Workflow: "self-documenting variable and function names; no abbreviations unless industry-standard." AGENTS.md §3: "Self-explanatory naming conventions (variables, functions, classes). DRY." Sufficient for code-review enforcement on a single-developer project.

---

## Requirement Consistency

- [x] CHK017 — Are the ownership enforcement requirements consistent between spec (FR-005, FR-001) and plan (dual-guard: controller null-check + service-layer scope)? [Consistency, Spec §FR-005, Plan §Country Ownership Enforcement]
  > ✅ spec §FR-005 requires 403 for cross-country. plan.md §Admin with No Country Guard and §Country Ownership Enforcement describe the dual-layer approach. Consistent: both layers enforce the same rule.

- [x] CHK018 — Are Arabic message requirements consistent across all four CRUD operations (index empty state, create success, edit success, delete success, all validation errors)? [Consistency, Spec §FR-007, §US1-3, §US2-3, §US3-2, §US4-1]
  > ✅ contracts/ajax-contracts.md defines success and error messages for store, update, destroy, and 403. Spec §US1 Scenario 3 requires an Arabic empty-state message (text unspecified — see CHK033). All operational messages are consistent and documented.

- [x] CHK019 — Is the `tableAction()` helper usage requirement consistent with the plan's UUID-based routing (i.e., passing `$brand->uuid` not `$brand->id`)? [Consistency, Plan §Architecture, Spec §FR-009]
  > ✅ contracts/ajax-contracts.md: "action column uses tableAction($brand->uuid)." plan.md Constitution Check §V: "tableAction($brand->uuid) used." tasks.md Notes: "never pass $brand->id." Three-way consistent.

- [x] CHK020 — Are validation rules in `StoreBrandRequest` and `UpdateBrandRequest` defined consistently — do both enforce the same 2–255 character constraint? [Consistency, Spec §FR-003, §FR-011]
  > ✅ data-model.md §Validation Rules shows min:2, max:255 in both FormRequest rule sets. tasks.md T013 and T018 repeat this. spec §FR-011 is the canonical definition; §FR-003 references it.

- [x] CHK021 — Is the `SoftDeletes` behavior consistent with the DataTable query? (i.e., does the plan require that `BrandService::getBrandsDataTable()` automatically excludes soft-deleted rows via Eloquent global scope?) [Consistency, Spec §FR-006, Plan §Architecture]
  > ✅ tasks.md Notes explicitly confirms: "Soft-deleted brands are excluded automatically by Eloquent's SoftDeletes global scope — no manual whereNull needed." The Brand model's global scope applies to all queries, including DataTable queries.

---

## Acceptance Criteria Quality

- [x] CHK022 — Can the requirement "controllers MUST NOT contain database queries" be objectively verified via code review? Is a concrete detection method specified? [Measurability, Plan §Constitution Check]
  > ✅ Binary check: grep BrandController.php for `Brand::`, `DB::`, `->where(`, `->find(`, etc. Constitution §I provides the clear prohibition. No ambiguity in detection.

- [x] CHK023 — Is the criterion "DataTable response < 1s for up to 1,000 brands per country" measurable and tied to a concrete test procedure? [Measurability, Plan §Performance Goals]
  > ✅ plan.md §Performance Goals defines the threshold. quickstart.md Scenario 1 is the manual benchmark procedure. Automated performance testing is out of scope for this feature. Acceptable for an admin panel at this scale.

- [x] CHK024 — Is SC-002 ("100% of cross-country access attempts are blocked") measurable with a defined testing approach? [Measurability, Spec §SC-002]
  > ✅ quickstart.md Scenarios 3, 4 define explicit URL-crafting tests for edit and delete. Scenario 5 covers the null-country case. Three concrete test procedures defined.

- [x] CHK025 — Are the Constitution Check gates in plan.md structured as checkable pass/fail criteria rather than narrative assertions? [Measurability, Plan §Constitution Check]
  > ✅ plan.md §Constitution Check is a table with ✅ PASS / explicit verification text for each principle. Binary pass/fail structure.

---

## Scenario Coverage

- [x] CHK026 — Are requirements defined for the **edit** flow when an admin submits a name that is unique globally but duplicates another brand in the same country (excluding self)? [Coverage, Spec §FR-008, Gap]
  > ✅ spec §FR-008 requires uniqueness per country. data-model.md §UpdateBrandRequest and tasks.md T018 document the `->ignore($uuid, 'uuid')->where('country_id', ...)` rule that correctly handles the self-exclusion + country scope together.

- [x] CHK027 — Are requirements defined for the DataTable AJAX endpoint's behavior when `country_id` is null (not just for the page controller)? [Coverage, Exception Flow, Gap]
  > ✅ tasks.md T011 specifies the guard fires in `index()` for both AJAX and page requests ("return DataTable JSON on AJAX **or** render view otherwise"). tasks.md T025 confirms the guard covers all six methods including index. The AJAX path is guarded.

- [ ] CHK028 — Are requirements defined for what the Service returns when `findForCountry()` finds the brand but it belongs to a different country — does it return null, throw, or call `abort()`? [Coverage, Plan §Country Ownership Enforcement]
  > ⚠️ **OPEN GAP** (analyze finding I2): Current plan uses `Brand::where('uuid', $uuid)->where('country_id', $countryId)->firstOrFail()` which returns **404** (ModelNotFoundException) for both "UUID doesn't exist" AND "UUID exists but wrong country." Spec §FR-005 requires **403** for cross-country access. These are conflated. The two-step resolution (find by UUID first → 404 if absent; then check country_id → 403 if mismatch) is not documented.
  > **Action needed**: Update tasks.md T019 to describe the two-step lookup or confirm the 404-for-cross-country behavior is intentionally accepted.

- [x] CHK029 — Is there a requirement covering the behavior when `Str::uuid()` is called but a UUID collision occurs (however unlikely)? [Coverage, Edge Case, Gap]
  > ✅ The `UNIQUE` constraint on `brands.uuid` (data-model.md §Indexes) is the DB-level guard. UUID v4 collision probability is negligible at this feature's scale (hundreds of brands). No application-level requirement needed. Accepted as-is.

- [x] CHK030 — Are requirements defined for the create form's behavior when the admin's session expires mid-submission (CSRF token expired)? [Coverage, Exception Flow, Gap]
  > ✅ Laravel's `VerifyCsrfToken` middleware returns 419 TokenMismatchException on expired tokens. The `my_form` AJAX layer (existing infrastructure) handles non-2xx responses. No feature-specific requirement needed — covered by the existing AJAX error handling convention.

---

## Edge Case Coverage

- [x] CHK031 — Is the edge case of a brand name containing only whitespace characters addressed in the validation requirements? [Edge Case, Spec §FR-011, Gap]
  > ✅ tasks.md T026 explicitly addresses whitespace-only names: "reject whitespace-only names via 'min:2' after trimming (add 'bail' and verify Laravel trims by default)." Laravel's `TrimStrings` middleware trims input before validation.

- [x] CHK032 — Is the edge case of a syntactically invalid UUID in the URL (e.g., `not-a-uuid`) addressed with a defined HTTP response? [Edge Case, Spec §Edge Cases]
  > ✅ spec §Edge Cases: "Return a 404 response; do not expose database structure in the error." The `->firstOrFail()` in `findForCountry()` returns 404 for any non-matching UUID string, including malformed ones. Laravel's exception handler returns the standard 404 page.

- [ ] CHK033 — Is the requirement for the empty-state message (no brands in the admin's country) defined consistently for both Arabic content AND DataTable rendering behavior? [Edge Case, Spec §US1 Scenario 3]
  > ⚠️ **OPEN GAP** (analyze finding C4): spec §US1 Scenario 3 says "an empty-state message is shown (in Arabic)" but the exact Arabic string is not specified anywhere. Additionally, no task configures the DataTable `language.emptyTable` option. The behavior is specified but the content and rendering mechanism are not.
  > **Action needed**: Define the Arabic empty-state string (e.g., "لا توجد ماركات مسجلة") and add a note in T012 to set the DataTable `language.emptyTable` config option.

- [x] CHK034 — Is the requirement that soft-deleted brands must be excluded from uniqueness validation (`->whereNull('deleted_at')`) explicitly stated? [Edge Case, Plan §Unique Constraint for Duplicate Names]
  > ✅ plan.md §Unique Constraint: `->whereNull('deleted_at')` is shown in the StoreBrandRequest code snippet. data-model.md §StoreBrandRequest repeats it. tasks.md T013/T018 include it. tasks.md Notes states the exception explicitly.

- [x] CHK035 — Is there a requirement covering concurrent duplicate-name submissions from the same admin (race condition on the unique constraint)? [Edge Case, Gap]
  > ✅ The composite UNIQUE index on `(name, country_id)` in `brands` (data-model.md §Indexes) handles this at the DB level — concurrent inserts with the same name + country would cause a constraint violation. Application-level race condition handling is out of scope at this scale.

---

## Non-Functional Requirements

- [x] CHK036 — Are security requirements for the DataTable AJAX endpoint (e.g., authentication check, country scope on the server side) explicitly defined in the contracts? [Coverage, Spec §FR-001, contracts/ajax-contracts.md]
  > ✅ contracts/ajax-contracts.md §Route Summary: "All routes are prefixed with /admin and protected by the admin middleware." §1 GET /admin/brands: requires `X-Requested-With: XMLHttpRequest`. tasks.md T011 guards country_id on the AJAX path.

- [x] CHK037 — Is there a requirement that no inline `<style>` or `<script>` blocks appear in Blade views, and is this verifiable from the plan? [Completeness, Plan §Constraints]
  > ✅ plan.md §Constraints: "No inline <style> or <script> in Blade views." Constitution §Development Workflow repeats this. Verifiable by grep on the Blade files.

- [x] CHK038 — Is a requirement defined for the `BrandController`'s response format when returning JSON for AJAX delete (message field, HTTP status code)? [Completeness, Gap]
  > ✅ contracts/ajax-contracts.md §6 DELETE: success → 200 `{"message": "تم الحذف بنجاح"}`, cross-country → 403 `{"message": "غير مصرح لك بهذا الإجراء"}`. Fully specified.

- [x] CHK039 — Is there a specified requirement for migration rollback safety — i.e., `down()` methods being defined on all three migrations? [Completeness, Gap]
  > ✅ tasks.md T003, T004, T005 each explicitly include "define down() to drop the table/column" in their descriptions.

- [x] CHK040 — Are code-style requirements (self-documenting names, no abbreviations) traceable to a concrete enforcement mechanism (code review gate, linter, etc.)? [Non-Functional, Ambiguity]
  > ✅ Enforcement mechanism is code review (no linter in this static-asset PHP project). Constitution §Governance requires all code reviews to verify compliance with principles before considering a feature complete. Adequate for this project type and team size.

---

## Dependencies & Assumptions

- [x] CHK041 — Is the assumption "the `admins` table already has `country_id`" validated against the actual database schema before tasks.md is written? [Assumption, Spec §Assumptions]
  > ✅ research.md §Storage: "admins.country_id — column must be added via migration (not present in 2024_07_14_175120_create_admins_table.php) — confirmed by scanning migrations." Assumption disproved and converted to a migration task (T004).

- [x] CHK042 — Is the assumption "Yajra DataTables assets are already loaded" verified with a specific reference to the layout file that includes them? [Assumption, Spec §Assumptions]
  > ✅ research.md §Primary Dependencies: "yajra/laravel-datatables-oracle — already used by AdminService for server-side DataTables." Confirmed by existing AdminService usage in the codebase. Sufficient verification for this assumption.

- [x] CHK043 — Is the dependency on `admin()->user()->country_id` being non-null documented as a prerequisite that upstream code (auth, admin provisioning) must guarantee? [Dependency, Plan §Admin with No Country Guard]
  > ✅ spec §Assumptions: "The admins table already has a country_id column; the relationship between Admin and Country is already established." plan.md §Admin with No Country Guard defines the guard as the controller's responsibility. The dependency chain is clear.

- [x] CHK044 — Is there a requirement that the three prerequisite migrations run **before** any Service or Model code is introduced, and is this ordering enforced in tasks.md? [Dependency, Plan §Summary]
  > ✅ tasks.md Phase 2 §CRITICAL: "Complete and run migrations before writing any Service or Model code." T006 (php artisan migrate) precedes T007/T008/T009 (models) which precede T010 (BrandService). Order enforced.

- [ ] CHK045 — Is the dependency on the existing `x-list.card`, `x-list.modal`, and `x-form.input` Blade components documented with their expected prop signatures? [Dependency, Gap]
  > ⚠️ **OPEN GAP**: research.md mentions these components exist but their prop signatures are not documented anywhere in the feature artifacts. tasks.md T012 and T017 reference `x-form.input` without specifying which props to pass (e.g., `name`, `label`, `value`, `type`). A developer unfamiliar with the component would need to inspect the source.
  > **Action needed**: Inspect the existing Blade component definitions (e.g., `resources/views/components/form/input.blade.php`) during T017/T022 and use the same prop conventions already in use in other admin views.

---

## Ambiguities & Conflicts

- [x] CHK046 — Is it clear whether `BrandController::destroy()` should return a JSON response (AJAX) or a redirect? The spec mentions "AJAX delete handler" — is this explicitly reconciled with the REST resource route? [Ambiguity, Spec §US4 Scenario 1]
  > ✅ contracts/ajax-contracts.md §6 DELETE explicitly defines the JSON response format: 200 `{"message": "تم الحذف بنجاح"}`. The ambiguity is resolved — destroy() always returns JSON, consistent with the existing delete convention used by the `my_form`/delete JS handlers.

- [x] CHK047 — Is there a potential conflict between `Route::resource('brands', 'BrandController')` using `{brand}` as the default wildcard and the plan's requirement to use `{uuid}`? Is the route key override documented? [Conflict, Plan §UUID as Route Parameter]
  > ✅ tasks.md T001 explicitly resolves this: `->parameters(['brands' => 'uuid'])` renames the wildcard from `{brand}` to `{uuid}`. plan.md §UUID as Route Parameter documents the decision. No conflict in the final implementation plan.

- [x] CHK048 — Is the term "reuse the existing DataTable infrastructure" specific enough to indicate which JS initialization pattern to follow, or does it leave too much to developer interpretation? [Ambiguity, Spec §Assumptions]
  > ✅ research.md confirms existing DataTable usage in AdminService. tasks.md T012 specifies `serverSide: true` (implied via Yajra) and AJAX URL pointing to `route('brands.index')`. The `#editBtn`/`.delete` DataTable action conventions are defined in AGENTS.md §6.

- [x] CHK049 — Is the responsibility for the "no-country" guard (controller vs. middleware vs. service) defined unambiguously in a single location in the plan? [Ambiguity, Plan §Admin with No Country Guard]
  > ✅ plan.md §Admin with No Country Guard: "BrandController MUST check admin()->user()->country_id at the top of every method." Controller is the single, explicit owner of this guard. Service provides a second line via scoped queries. No ambiguity.

---

## Notes

- **Focus**: Clean code quality and correctness of requirements.
- **Audience**: Author / reviewer before `/speckit-tasks` and `/speckit-implement`.
- **Depth**: Standard review gate.
- **Constitution constraints applied**: Principles I–V from `constitution.md` drove CHK001–CHK010, CHK017, CHK022, CHK037, CHK040.

### Review Summary (2026-07-01)

| Result | Count | Items |
|--------|-------|-------|
| ✅ Confirmed | 46 | All except CHK028, CHK033, CHK045 |
| ⚠️ Open Gap | 3 | CHK028, CHK033, CHK045 |

### Open Gaps — Action Plan

| CHK | Gap | Recommended Action |
|-----|-----|--------------------|
| **CHK028** | `findForCountry()` 404/403 conflation | During T019: use two-step lookup — `firstOrFail()` on UUID alone (→ 404), then check `country_id` (→ abort 403 if mismatch) |
| **CHK033** | Arabic empty-state string not defined; DataTable `language.emptyTable` not configured | During T012: add `language: { emptyTable: 'لا توجد ماركات مسجلة لبلدك' }` to DataTable JS init |
| **CHK045** | `x-form.input` prop signatures not documented in feature artifacts | During T017/T022: inspect existing component at `resources/views/components/` and follow the same prop conventions used in other admin create/edit forms |

> These 3 gaps are **implementation-time resolutions** — they do not require spec or plan updates before `/speckit-implement` begins.
