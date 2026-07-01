# Quickstart Validation Guide: Brand CRUD (Country-Scoped)

**Feature**: `001-brand-crud`
**Date**: 2026-07-01

This guide describes how to validate the Brand CRUD feature works end-to-end
after implementation is complete.

---

## Prerequisites

1. XAMPP stack running (Apache + MySQL).
2. `.env` configured with `DB_DATABASE=ai_tools_practice`.
3. All migrations for this feature have been run (see Setup below).
4. At least one Country and one Admin (with `country_id` set) exist in the database.
5. Admin panel accessible at `http://localhost/Code/Projects/Ai/public/admin`.

---

## Setup

```bash
# Run all pending migrations (countries, admins.country_id, brands)
php artisan migrate

# (Optional) Seed test data — Countries and Admins
php artisan db:seed --class=CountrySeeder
php artisan db:seed --class=AdminBrandSeeder
```

---

## Validation Scenarios

### Scenario 1 — Country-Scoped Listing (US1 / FR-001)

**Goal**: Confirm that admins only see their own country's brands.

1. Log in as **Admin A** (assigned to Country X).
2. Navigate to `http://.../admin/brands`.
3. **Expected**: DataTable shows only brands where `country_id = Country X`.
4. Log out. Log in as **Admin B** (assigned to Country Y).
5. Navigate to the same URL.
6. **Expected**: DataTable shows only brands from Country Y. Brands from Country X
   are invisible.

✅ Pass when: Zero cross-country brands appear in either admin's listing.

---

### Scenario 2 — Create Brand (US2 / FR-002, FR-008, FR-011)

**Goal**: Confirm brand creation assigns UUID and country, and validates input.

1. As Admin A, click the **"إضافة"** button on the Brands index.
2. Leave name empty → submit.
   - **Expected**: Arabic validation error "اسم الماركة مطلوب".
3. Enter `"A"` (1 character) → submit.
   - **Expected**: Arabic validation error for minimum length.
4. Enter `"نايك"` → submit.
   - **Expected**: Arabic success toast. New row "نايك" appears in DataTable.
5. Check the database: `brands` record has `uuid` (not null), `country_id = Admin A's country_id`, `deleted_at = null`.
6. Try to create another brand named `"نايك"` (same country).
   - **Expected**: Arabic duplicate validation error.

✅ Pass when: All validations fire correctly and the record has correct `uuid` + `country_id`.

---

### Scenario 3 — Edit Brand (US3 / FR-003)

**Goal**: Confirm edit pre-populates form and saves correctly.

1. As Admin A, click **Edit** on the "نايك" brand row.
   - **Expected**: Modal opens with name field pre-filled as "نايك".
2. Change name to `"أديداس"` → submit.
   - **Expected**: Arabic success message. DataTable row updates to "أديداس".
3. Try to edit a brand from Country Y by crafting a URL:
   `GET /admin/brands/{uuid-from-country-Y}/edit`
   - **Expected**: 403 Forbidden response (or Arabic error in the modal).

✅ Pass when: Edit persists the update and cross-country edit is blocked.

---

### Scenario 4 — Soft Delete (US4 / FR-004, FR-006)

**Goal**: Confirm soft delete sets `deleted_at` and removes brand from listing.

1. As Admin A, click **Delete** on the "أديداس" row.
   - **Expected**: JS confirmation dialog appears.
2. Cancel the dialog.
   - **Expected**: No request sent; brand remains in table.
3. Click Delete again. Confirm the dialog.
   - **Expected**: Brand disappears from DataTable. Arabic success message shown.
4. Check the database: `brands` record exists with `deleted_at` timestamp set.
   The record is NOT permanently deleted.
5. Try to delete a brand from Country Y by crafting a DELETE request:
   `DELETE /admin/brands/{uuid-from-country-Y}`
   - **Expected**: 403 Forbidden.

✅ Pass when: Soft delete sets `deleted_at`, record persists in DB, and
cross-country delete is blocked.

---

### Scenario 5 — No Country Assigned (Edge Case)

**Goal**: Confirm admin with no country is blocked from Brands section.

1. Update Admin A's `country_id` to `null` in the database.
2. Log in as Admin A. Navigate to `/admin/brands`.
   - **Expected**: Access is blocked with an Arabic error message.

✅ Pass when: Admin with null `country_id` cannot reach any brand page.

---

## Expected Database State After Full Validation

```sql
-- brands table should have:
SELECT uuid, name, country_id, deleted_at FROM brands;
-- Shows 'أديداس' row with deleted_at set (soft deleted)

-- admins table should have country_id column:
DESCRIBE admins;
-- Shows country_id column

-- countries table should exist:
SHOW TABLES LIKE 'countries';
```

---

## References

- [Data Model](./data-model.md) — entity definitions, column specs
- [AJAX Contracts](./contracts/ajax-contracts.md) — request/response formats
- [Spec](./spec.md) — acceptance criteria and functional requirements
