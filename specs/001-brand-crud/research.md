# Research: Brand CRUD (Country-Scoped)

**Feature**: `001-brand-crud`
**Date**: 2026-07-01

---

## 1. Technical Context Resolution

All unknowns from the Technical Context section are resolved below.

---

### Decision: Language / Framework Version

- **Decision**: PHP 8.2 + Laravel 12.x (confirmed from `composer.json` and `AGENTS.md`)
- **Rationale**: Already in use; no migration needed.
- **Alternatives considered**: N/A — project is established.

---

### Decision: Primary Dependencies

- **Decision**:
  - `yajra/laravel-datatables-oracle` — already used by `AdminService` for server-side DataTables.
  - Blade component system (`x-list.card`, `x-list.modal`, `x-form.input`) — already in use by Admin views.
  - `Str::uuid()` (Laravel built-in) — for UUID generation; no extra package required.
  - `SoftDeletes` trait (Laravel built-in Eloquent) — for soft deletion.
- **Rationale**: Zero new dependencies; all capabilities already available.
- **Alternatives considered**: `ramsey/uuid` package — rejected because Laravel's `Str::uuid()` is sufficient and already available.

---

### Decision: Storage

- **Decision**: MySQL (`ai_tools_practice`) — already configured in `.env`.
- **Rationale**: Existing project database.
- **Tables required**:
  1. `countries` — must be created (does not exist yet; confirmed by scanning migrations).
  2. `admins.country_id` — column must be added via migration (not present in `2024_07_14_175120_create_admins_table.php`).
  3. `brands` — new table to be created.

---

### Decision: UUID Strategy

- **Decision**: Store UUID as a `uuid` column type (CHAR 36). Generate via `Str::uuid()` in `BrandService::storeBrand()`.
- **Rationale**: Keeps UUID generation in the Service layer (per constitution principle II), uses no extra packages, and is database-portable.
- **Alternatives considered**: Auto-generating via Eloquent `boot()` method — rejected because it moves logic to the Model layer, violating SRP.

---

### Decision: Country Ownership Enforcement Pattern

- **Decision**: Use a **service-level scope** inside `BrandService`. The service always applies `where('country_id', admin()->user()->country_id)` before any query. Additionally, a dedicated `BrandAuthorizationService` (or inline check in service) verifies ownership on edit/delete by re-querying with the scoped filter.
- **Rationale**:
  - Controller stays thin — it never receives a Brand model it has no right to access.
  - Service-layer guard aligns with Constitution Principle I (Clean Architecture) and Principle II (Service-Layer Supremacy).
  - Using `findOrFail` with a country-scoped query automatically raises a `ModelNotFoundException` → 404 if UUID doesn't match. For deliberate cross-country access (URL-crafting), a 403 is appropriate — achieved by a scope check before the query.
- **Pattern chosen**: The `BrandService` injects the authenticated admin's `country_id` into every query. Route model binding is NOT used because the UUID must be scoped; instead the controller passes the UUID to the service which returns the model or aborts(403/404).
- **Alternatives considered**: Laravel Policies — valid alternative, but adds a Policy class for a single ownership check. Rejected to keep the solution minimal. Can be added in a future iteration.

---

### Decision: Soft Delete Route Model Binding

- **Decision**: Do NOT use Laravel route model binding with `{brand}` parameter directly on the controller. Instead, controllers receive the raw UUID from the route and pass it to the service. The service resolves the brand with a country-scoped query and throws an authorization exception if needed.
- **Rationale**: Route model binding ignores country scope — it would find any brand by UUID, creating a security hole. The service is the enforcement point.
- **Alternatives considered**: Route model binding + Policy — deferred to future iteration.

---

### Decision: Soft Delete UI Confirmation

- **Decision**: Use the existing project JS confirmation mechanism (`class="delete"` button + existing AJAX handler in `ajax.blade.php` include).
- **Rationale**: Confirmed in clarification session (Q1, 2026-07-01). Consistent with existing pattern used in Admin CRUD.
- **Alternatives considered**: N/A — clarified by user.

---

### Decision: Brand Name Uniqueness Scope

- **Decision**: Unique within same country. Validation rule: `unique:brands,name,NULL,id,country_id,{admin_country_id}`.
- **Rationale**: Two brands named "Nike" can exist if they belong to different countries. Within one country, name must be unique.
- **Alternatives considered**: Global unique — rejected because brand names naturally repeat across markets.

---

### Decision: Dependency Injection Pattern

- **Decision**: Constructor injection via Laravel's service container. `BrandController` declares `BrandService $brandService` in its constructor. No manual `new` instantiation.
- **Rationale**: Aligns with Constitution Principle II and the user's explicit SOLID/DI requirement. Consistent with `AdminController` pattern already in use.
- **Pattern**: Laravel auto-resolves the service from the container; no binding registration needed for concrete classes.

---

## 2. SOLID Application Map

| Principle | Application in this Feature |
|---|---|
| **S** — Single Responsibility | Controller handles HTTP; Service handles business logic; FormRequest handles validation; Model handles schema. |
| **O** — Open/Closed | `BrandService` methods are closed for modification; new filtering behaviors can be added via method extension. |
| **L** — Liskov | `Brand` model extends `Model`; substitutable wherever Eloquent models are expected. |
| **I** — Interface Segregation | `BrandService` exposes only the operations needed by `BrandController`; no fat interfaces. |
| **D** — Dependency Inversion | `BrandController` depends on `BrandService` (concrete) injected via constructor; relies on Laravel container abstraction. |

---

## 3. Prerequisite Gap Analysis

| Gap | Action Required | Priority |
|---|---|---|
| `countries` table missing | Create migration + `Country` model | BLOCKING — all other work depends on this |
| `admins.country_id` missing | Add column via migration | BLOCKING — country-scoped queries impossible without this |
| `brands` table missing | Create migration + `Brand` model with `SoftDeletes` | Core feature |

---

## 4. File Naming Conventions (matching existing project)

| Layer | Convention | Example |
|---|---|---|
| Controller | `{Entity}Controller.php` in `App\Http\Controllers\Admin\` | `BrandController.php` |
| Service | `{Entity}Service.php` in `App\Services\Admin\` | `BrandService.php` |
| FormRequest | `Store{Entity}Request.php`, `Update{Entity}Request.php` in `App\Http\Requests\Admin\{Entity}\` | `StoreBrandRequest.php` |
| Model | `{Entity}.php` in `App\Models\` | `Brand.php` |
| Migration | `YYYY_MM_DD_HHMMSS_create_{table}_table.php` | `2026_07_01_000001_create_countries_table.php` |
| Views | `resources/views/Admin/{Entity}/index.blade.php` + `parts/{create,edit}.blade.php` | `Admin/Brand/index.blade.php` |
