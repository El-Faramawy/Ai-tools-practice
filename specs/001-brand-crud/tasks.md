# Tasks: Brand CRUD (Country-Scoped)

**Input**: Design documents from `specs/001-brand-crud/`

**Prerequisites**: plan.md ✅ | spec.md ✅ | data-model.md ✅ | contracts/ ✅ | research.md ✅ | quickstart.md ✅

**Tests**: Not requested in spec — no test tasks generated.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (US1–US4)
- Exact file paths included in all descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Register the Brands resource route and wire up the admin navigation entry. No user-story work begins here — this phase creates the skeleton that all subsequent phases build on.

- [x] T001 Register `Route::resource('brands', BrandController::class)` under the `admin` middleware group in `routes/web.php`, using `{uuid}` as the route parameter name via `->parameters(['brands' => 'uuid'])`
- [x] T002 Add a "Brands" navigation link to the admin sidebar partial in `resources/views/layouts/admin/inc/sidebar.blade.php` pointing to `route('brands.index')`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Database schema and base models that ALL user stories depend on. No user-story phase can begin until this phase is 100% complete.

**⚠️ CRITICAL**: Complete and run migrations before writing any Service or Model code.

- [x] T003 Create migration `database/migrations/2026_07_01_000001_create_countries_table.php` — columns: `id` (PK), `name` (string 100, NOT NULL), `timestamps`; define `down()` to drop the table
- [x] T004 Create migration `database/migrations/2026_07_01_000002_add_country_id_to_admins_table.php` — add nullable `country_id` (unsignedBigInteger) FK → `countries.id` ON DELETE SET NULL to `admins`; define `down()` to drop the column
- [x] T005 Create migration `database/migrations/2026_07_01_000003_create_brands_table.php` — columns: `id` (PK), `uuid` (uuid, NOT NULL, UNIQUE), `name` (string 255, NOT NULL), `country_id` (unsignedBigInteger, NOT NULL, FK → `countries.id` ON DELETE CASCADE), `softDeletes()`, `timestamps`; add composite UNIQUE index on `(name, country_id)` and regular INDEX on `country_id`; define `down()` to drop the table
- [x] T006 Run `php artisan migrate` to apply all three migrations in dependency order
- [x] T007 [P] Create Eloquent model `app/Models/Country.php` — `$guarded = []`, relationships: `hasMany(Brand::class)`, `hasMany(Admin::class)`
- [x] T008 [P] Create Eloquent model `app/Models/Brand.php` — use `SoftDeletes` trait, `$guarded = []`, relationship: `belongsTo(Country::class)`, override `getRouteKeyName()` to return `'uuid'` (informational only — route model binding is NOT used)
- [x] T009 Update existing model `app/Models/Admin.php` — add `belongsTo(Country::class)` relationship method

**Checkpoint**: Migrations applied ✅ | Country, Brand models created ✅ | Admin model updated ✅ — user story implementation can now begin.

---

## Phase 3: User Story 1 — Browse My Country's Brands (Priority: P1) 🎯 MVP

**Goal**: An admin visiting `/admin/brands` sees a paginated, searchable DataTable listing **only** the brands belonging to their assigned country.

**Independent Test** (from quickstart.md Scenario 1):
Log in as Admin A (Country X) → navigate to `/admin/brands` → confirm only Country X brands appear.
Log out → log in as Admin B (Country Y) → confirm only Country Y brands appear. Zero cross-country brands in either listing.

### Implementation for User Story 1

- [x] T010 [US1] Create `app/Services/Admin/BrandService.php` — implement `getBrandsDataTable(int $countryId): mixed` using `Brand::where('country_id', $countryId)->latest()` and returning a Yajra DataTables instance with columns: `name`, `created_at`, and `action` (via `tableAction($brand->uuid)`)
- [x] T011 [US1] Create `app/Http/Controllers/Admin/BrandController.php` — constructor-inject `BrandService`; implement `index()`: guard `admin()->user()->country_id` (abort 403 with Arabic message if null), call `$this->brandService->getBrandsDataTable($countryId)` and return DataTable JSON on AJAX or render `Admin.Brand.index` view otherwise
- [x] T012 [US1] Create index Blade view `resources/views/Admin/Brand/index.blade.php` — extend admin layout, include DataTable markup with columns (Name, Created At, Actions), wire up DataTable JS initialization pointing to `route('brands.index')`, include create-modal trigger button labeled "إضافة"

**Checkpoint**: US1 fully functional — admin can view country-scoped brand listing ✅

---

## Phase 4: User Story 2 — Create a New Brand (Priority: P2)

**Goal**: An admin submits the Create Brand form; a new brand is stored with the admin's `country_id` and a system-generated UUID; admin is redirected/notified with an Arabic success message.

**Independent Test** (from quickstart.md Scenario 2):
Click "إضافة" → submit empty name → see Arabic error. Submit 1-char name → see Arabic length error. Submit "نايك" → brand appears in DataTable with correct `uuid` + `country_id`. Submit "نايك" again → see Arabic duplicate error.

### Implementation for User Story 2

- [x] T013 [P] [US2] Create `app/Http/Requests/Admin/Brand/StoreBrandRequest.php` — `authorize()` returns `true`; `rules()`: `name` → `['required', 'string', 'min:2', 'max:255', Rule::unique('brands','name')->where('country_id', admin()->user()->country_id)->whereNull('deleted_at')]`; `messages()` returns Arabic strings for all validation keys
- [x] T014 [US2] Add `storeBrand(array $data, int $countryId): Brand` method to `app/Services/Admin/BrandService.php` — creates brand with `uuid = Str::uuid()`, `name = $data['name']`, `country_id = $countryId`
- [x] T015 [US2] Add `create()` method to `app/Http/Controllers/Admin/BrandController.php` — guard `country_id`, return view `Admin.Brand.parts.create`
- [x] T016 [US2] Add `store(StoreBrandRequest $request)` method to `app/Http/Controllers/Admin/BrandController.php` — guard `country_id`, call `$this->brandService->storeBrand($request->validated(), $countryId)`, return JSON `{'message': 'تم الاضافة بنجاح'}`
- [x] T017 [US2] Create create-form partial `resources/views/Admin/Brand/parts/create.blade.php` — `<form id="form" class="my_form" method="POST" action="{{ route('brands.store') }}">`, CSRF token, `x-form.input` component for the `name` field labeled in Arabic

**Checkpoint**: US2 fully functional — admin can create country-scoped brands with validation ✅

---

## Phase 5: User Story 3 — Edit an Existing Brand (Priority: P3)

**Goal**: Admin opens edit form pre-populated with current brand name, submits changes; brand is updated; cross-country edit attempts are rejected with 403.

**Independent Test** (from quickstart.md Scenario 3):
Click Edit on "نايك" → modal opens with name "نايك" pre-filled. Change to "أديداس" → save → DataTable shows "أديداس". Craft URL with foreign-country UUID → receive 403.

### Implementation for User Story 3

- [x] T018 [P] [US3] Create `app/Http/Requests/Admin/Brand/UpdateBrandRequest.php` — `authorize()` returns `true`; `rules()`: `name` → `['required', 'string', 'min:2', 'max:255', Rule::unique('brands','name')->ignore($this->route('uuid'), 'uuid')->where('country_id', admin()->user()->country_id)->whereNull('deleted_at')]`; Arabic `messages()`
- [x] T019 [US3] Add `findForCountry(string $uuid, int $countryId): Brand` and `updateBrand(Brand $brand, array $data): Brand` methods to `app/Services/Admin/BrandService.php` — `findForCountry` does `Brand::where('uuid', $uuid)->where('country_id', $countryId)->firstOrFail()` (raises 404 on UUID mismatch; controller handles 403 for country mismatch via null-country guard)
- [x] T020 [US3] Add `edit(string $uuid)` method to `app/Http/Controllers/Admin/BrandController.php` — guard `country_id` (abort 403), call `findForCountry($uuid, $countryId)` (abort 403 if null/not found in country), return view `Admin.Brand.parts.edit` with brand
- [x] T021 [US3] Add `update(UpdateBrandRequest $request, string $uuid)` method to `app/Http/Controllers/Admin/BrandController.php` — guard `country_id`, call `findForCountry` then `updateBrand`, return JSON `{'message': 'تم التعديل بنجاح'}`
- [x] T022 [US3] Create edit-form partial `resources/views/Admin/Brand/parts/edit.blade.php` — `<form id="form" class="my_form" method="POST" action="{{ route('brands.update', $brand->uuid) }}">`, `@method('PUT')`, CSRF, `x-form.input` for name pre-populated with `$brand->name`

**Checkpoint**: US3 fully functional — admin can edit country-scoped brands; cross-country edit blocked ✅

---

## Phase 6: User Story 4 — Soft-Delete a Brand (Priority: P4)

**Goal**: Admin confirms deletion dialog; brand's `deleted_at` is set via AJAX; brand disappears from DataTable; record persists in DB. Cross-country delete returns 403. Cancelling dialog sends no request.

**Independent Test** (from quickstart.md Scenario 4):
Click Delete → confirm dialog → brand disappears from DataTable + Arabic success message. DB record has `deleted_at` set. Craft DELETE request for foreign-country UUID → receive 403.

### Implementation for User Story 4

- [x] T023 [US4] Add `deleteBrand(string $uuid, int $countryId): void` method to `app/Services/Admin/BrandService.php` — call `findForCountry($uuid, $countryId)` then `$brand->delete()` (Eloquent soft delete sets `deleted_at`)
- [x] T024 [US4] Add `destroy(string $uuid)` method to `app/Http/Controllers/Admin/BrandController.php` — guard `country_id` (abort 403), call `$this->brandService->deleteBrand($uuid, $countryId)`, return JSON `{'message': 'تم الحذف بنجاح'}` with HTTP 200

**Checkpoint**: US4 fully functional — soft-delete works via AJAX; cross-country delete blocked ✅

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Edge-case hardening, no-country guard validation, and end-to-end quickstart validation across all four stories.

- [x] T025 [P] Verify the no-country guard fires correctly on ALL six controller methods (`index`, `create`, `store`, `edit`, `update`, `destroy`) by tracing the `abort(403, 'غير مصرح لك بهذا الإجراء')` call in each method in `app/Http/Controllers/Admin/BrandController.php`
- [x] T026 [P] Add `'string'` validation rule to both `StoreBrandRequest` and `UpdateBrandRequest` in `app/Http/Requests/Admin/Brand/` to reject whitespace-only names via `'min:2'` after trimming (add `'bail'` and verify Laravel trims by default or add explicit `trim` middleware/cast)
- [x] T027 [P] Confirm the `tableAction()` helper call in `BrandService::getBrandsDataTable()` passes `$brand->uuid` (not `$brand->id`) so that DataTable edit/delete buttons carry the correct UUID `data-id` attribute, matching the AJAX contracts in `contracts/ajax-contracts.md`
- [ ] T028 Run full end-to-end validation following `specs/001-brand-crud/quickstart.md` — all five scenarios (Scenario 1–5) must pass

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: No dependencies — start immediately
- **Phase 2 (Foundational)**: Depends on Phase 1 completion — **BLOCKS all user story phases**
- **Phase 3 (US1)**: Depends on Phase 2 — no dependency on US2/US3/US4
- **Phase 4 (US2)**: Depends on Phase 2 — **also depends on Phase 3** (BrandController and BrandService already exist, so US2 extends them)
- **Phase 5 (US3)**: Depends on Phase 4 (extends BrandService + BrandController)
- **Phase 6 (US4)**: Depends on Phase 5 (extends BrandService + BrandController)
- **Phase 7 (Polish)**: Depends on Phases 3–6 all complete

### Within Each Phase

- Migrations (T003–T005) must be created before running `php artisan migrate` (T006)
- Models (T007–T009) must exist before BrandService (T010) is written
- BrandService must exist before BrandController (T011) is written
- Controller methods (store/update/destroy) must exist before their corresponding views (partials)
- Tasks marked `[P]` within a phase operate on different files and can be done in parallel

### Parallel Opportunities

```
Phase 2:
  T003 (create_countries migration) → parallel with nothing (no dependency)
  T004 (add_country_id migration)   → depends on T003 (FK to countries)
  T005 (create_brands migration)    → depends on T003 (FK to countries)
  T007 Country model [P]            → parallel with T008 Brand model [P] → parallel with T009 Admin model
  (after T006 migrate is done)

Phase 4 (US2):
  T013 StoreBrandRequest [P]        → parallel with T014 storeBrand service method

Phase 5 (US3):
  T018 UpdateBrandRequest [P]       → parallel with T019 service methods

Phase 7:
  T025, T026, T027 [P]              → all can run in parallel (different files)
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (routes + nav)
2. Complete Phase 2: Foundational (migrations + models) — **run migrations before continuing**
3. Complete Phase 3: US1 (BrandService DataTable + BrandController index + index view)
4. **STOP and VALIDATE**: Follow quickstart.md Scenario 1 — country-scoped listing works

### Incremental Delivery

| Step | Phases Complete | Capability Unlocked |
|------|----------------|---------------------|
| 1 | 1 + 2 | Database schema + models ready |
| 2 | + 3 (US1) | Browse brands — MVP |
| 3 | + 4 (US2) | Create brands |
| 4 | + 5 (US3) | Edit brands |
| 5 | + 6 (US4) | Soft-delete brands |
| 6 | + 7 (Polish) | All edge cases hardened — ready for release |

---

## Notes

- `[P]` tasks operate on different files with no shared state — safe to parallelize
- `[US#]` label maps each task to its user story for traceability against spec.md
- The `admin()` helper is used in all layers — never `Auth::guard('admin')`
- The `my_form` CSS class on forms triggers the existing AJAX submission layer automatically
- The `tableAction($brand->uuid)` helper generates edit/delete buttons; never pass `$brand->id`
- Soft-deleted brands are excluded from all queries automatically by Eloquent's `SoftDeletes` global scope — no manual `whereNull('deleted_at')` needed in service queries (exception: uniqueness rules in FormRequests, which bypass Eloquent)
- Commit after each phase checkpoint for clean git history
