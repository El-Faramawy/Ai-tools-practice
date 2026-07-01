# Implementation Plan: Brand CRUD (Country-Scoped)

**Branch**: `001-brand-crud` | **Date**: 2026-07-01 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/001-brand-crud/spec.md`

---

## Summary

Implement a full country-scoped Brand CRUD feature for the admin panel using
**Clean Architecture**, **SOLID principles**, and **Dependency Injection**. Each
admin can only list, create, edit, and soft-delete brands that belong to their
assigned country. The feature requires two prerequisite migrations (`countries`
table + `admins.country_id` column) before the core Brand implementation can
begin.

**Technical approach**: Service-layer ownership enforcement via country-scoped
Eloquent queries. UUID-based routing with manual service resolution (no route
model binding) to prevent cross-country access. Constructor-injected `BrandService`
in `BrandController`. All validation in dedicated `FormRequest` classes. All
messages in Arabic.

---

## Technical Context

**Language/Version**: PHP 8.2 / Laravel 12.x

**Primary Dependencies**:
- `yajra/laravel-datatables-oracle` — server-side DataTable rendering (already installed)
- Blade component system (`x-list.card`, `x-list.modal`, `x-form.input`) — already in use
- `Illuminate\Support\Str::uuid()` — UUID generation (no new package)
- `Illuminate\Database\Eloquent\SoftDeletes` — soft deletion (built-in)

**Storage**: MySQL — `ai_tools_practice` database

**Testing**: PHPUnit (Laravel default) — manual validation via `quickstart.md`

**Target Platform**: XAMPP local server (Apache + PHP 8.2 + MySQL)

**Project Type**: Web admin panel (Laravel MVC + AJAX DataTables)

**Performance Goals**: Standard admin panel — DataTable response < 1s for up to
1,000 brands per country.

**Constraints**: No new Composer/NPM packages. Static assets only in `public/Admin/`.
No inline `<style>` or `<script>` in Blade views.

**Scale/Scope**: Single admin panel; multiple countries; up to hundreds of brands
per country.

---

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Verification |
|-----------|--------|-------------|
| **I. Clean Architecture** | ✅ PASS | Route → Request → Controller → Service → Model. No layer bypassed. Controller has zero DB calls. |
| **II. Service-Layer Supremacy** | ✅ PASS | All queries, UUID generation, ownership checks, and soft-delete logic live in `BrandService`. |
| **III. Validation Isolation** | ✅ PASS | `StoreBrandRequest` and `UpdateBrandRequest` handle all validation. No `$request->validate()` in controllers. Messages in Arabic. |
| **IV. RESTful Design** | ✅ PASS | `Route::resource('brands', 'BrandController')`. All standard REST methods used. Route grouped under `admin` middleware. |
| **V. Simplicity, DRY & Helper Usage** | ✅ PASS | `tableAction($brand->uuid)` used. `admin()->user()->country_id` via helper. No new packages. Arabic messages. |

**Post-Design Re-check**: ✅ All gates pass. No violations to justify.

---

## Project Structure

### Documentation (this feature)

```text
specs/001-brand-crud/
├── plan.md              ← this file
├── research.md          ← Phase 0 output
├── data-model.md        ← Phase 1 output
├── quickstart.md        ← Phase 1 output
├── contracts/
│   └── ajax-contracts.md   ← Phase 1 output
├── checklists/
│   └── requirements.md
└── tasks.md             ← Phase 2 output (/speckit-tasks command)
```

### Source Code (repository root)

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── BrandController.php          ← NEW
│   ├── Middleware/                           (unchanged)
│   └── Requests/
│       └── Admin/
│           └── Brand/                       ← NEW directory
│               ├── StoreBrandRequest.php    ← NEW
│               └── UpdateBrandRequest.php   ← NEW
├── Models/
│   ├── Admin.php                            ← MODIFY (add country_id relationship)
│   ├── Brand.php                            ← NEW
│   └── Country.php                          ← NEW
└── Services/
    └── Admin/
        └── BrandService.php                 ← NEW

database/
└── migrations/
    ├── 2026_07_01_000001_create_countries_table.php         ← NEW
    ├── 2026_07_01_000002_add_country_id_to_admins_table.php ← NEW
    └── 2026_07_01_000003_create_brands_table.php            ← NEW

resources/
└── views/
    └── Admin/
        └── Brand/                           ← NEW directory
            ├── index.blade.php              ← NEW
            └── parts/
                ├── create.blade.php         ← NEW
                └── edit.blade.php           ← NEW

routes/
└── web.php                                  ← MODIFY (add brands resource route)
```

**Structure Decision**: Single Laravel project (Option 1 adapted). Follows the
established project convention: `Admin/` subdirectory for controllers, services,
requests, and views. No additional sub-projects or packages introduced.

---

## Complexity Tracking

> No constitution violations — this table is empty (no justifications required).

---

## Architecture Decisions

### Country Ownership Enforcement

The `BrandService` accepts the admin's `country_id` (obtained via `admin()->user()->country_id`)
as a parameter in every method that touches brand data. This keeps the service
testable and the controller thin:

```
BrandController::index()
  → BrandService::getBrandsDataTable(int $countryId)
      → Brand::where('country_id', $countryId)->latest()

BrandController::edit(string $uuid)
  → BrandService::findForCountry(string $uuid, int $countryId)
      → Brand::where('uuid', $uuid)->where('country_id', $countryId)->firstOrFail()
      → abort(403) if result is null / admin has no country
```

### Admin with No Country Guard

`BrandController` MUST check `admin()->user()->country_id` at the top of every
method. If null → `abort(403, 'غير مصرح لك بهذا الإجراء')`.

This guard is the FIRST line of defense; the service-layer scope is the SECOND.

### UUID as Route Parameter

Routes use `{uuid}` as the wildcard: `Route::resource('brands', 'BrandController')`.
Override `Route::bind` is NOT used. The controller passes `$uuid` directly to
the service. The service does `Brand::where('uuid', $uuid)->where('country_id', $countryId)->firstOrFail()`.

### Unique Constraint for Duplicate Names

The `unique` validation rule in `StoreBrandRequest` must be built dynamically
because `country_id` must come from the authenticated admin, not from user input:

```php
// In StoreBrandRequest::rules()
'name' => [
    'required', 'string', 'min:2', 'max:255',
    Rule::unique('brands', 'name')
        ->where('country_id', admin()->user()->country_id)
        ->whereNull('deleted_at'),
]
```

---

## Phase Deliverables Summary

| Phase | Deliverable | Status |
|-------|------------|--------|
| 0 — Research | `research.md` | ✅ Done |
| 1 — Data Model | `data-model.md` | ✅ Done |
| 1 — Contracts | `contracts/ajax-contracts.md` | ✅ Done |
| 1 — Quickstart | `quickstart.md` | ✅ Done |
| 2 — Tasks | `tasks.md` | ✅ Done |
| 3 — Implementation | All source files | ⏳ Pending (`/speckit-implement`) |
