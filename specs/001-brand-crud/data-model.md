# Data Model: Brand CRUD (Country-Scoped)

**Feature**: `001-brand-crud`
**Date**: 2026-07-01

---

## Entities

### 1. Country *(prerequisite — must exist before Brand)*

**Table**: `countries`

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | `unsignedBigInteger` | PK, auto-increment | |
| `name` | `string(100)` | NOT NULL | Country display name |
| `created_at` | `timestamp` | nullable | |
| `updated_at` | `timestamp` | nullable | |

**Eloquent Model**: `App\Models\Country`
- `$guarded = []`
- Relationship: `hasMany(Brand::class)`
- Relationship: `hasMany(Admin::class)`

---

### 2. Admin *(existing — requires column addition)*

**Table**: `admins` *(existing)*

**New column to add via migration**:

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `country_id` | `unsignedBigInteger` | nullable, FK → `countries.id`, onDelete SET NULL | Links admin to their country |

**Eloquent Model update**: `App\Models\Admin`
- Add relationship: `belongsTo(Country::class)`
- Add `country_id` to fillable (currently uses `$guarded = []` — no change needed)

---

### 3. Brand *(new — core of this feature)*

**Table**: `brands`

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | `unsignedBigInteger` | PK, auto-increment | Internal PK |
| `uuid` | `uuid` | NOT NULL, UNIQUE | System-generated via `Str::uuid()` |
| `name` | `string(255)` | NOT NULL | 2–255 characters |
| `country_id` | `unsignedBigInteger` | NOT NULL, FK → `countries.id`, onDelete CASCADE | Country ownership |
| `deleted_at` | `timestamp` | nullable | Soft delete sentinel |
| `created_at` | `timestamp` | nullable | Auto-managed |
| `updated_at` | `timestamp` | nullable | Auto-managed |

**Indexes**:
- `UNIQUE (name, country_id)` — enforces unique brand names within a country
- `INDEX (country_id)` — speeds up country-scoped listing queries
- `UNIQUE (uuid)` — global UUID uniqueness

**Eloquent Model**: `App\Models\Brand`
- Uses `SoftDeletes` trait
- `$guarded = []`
- Relationship: `belongsTo(Country::class)`
- Route key: UUID (override `getRouteKeyName()` returns `'uuid'` — **not** used for route model binding in controllers; UUID is resolved manually via service for security scoping)

---

## Validation Rules

### StoreBrandRequest

| Field | Rules |
|-------|-------|
| `name` | `required`, `string`, `min:2`, `max:255`, `unique:brands,name,NULL,id,country_id,{admin_country_id},deleted_at,NULL` |

*(The uniqueness rule scopes to the admin's country and excludes soft-deleted records.)*

### UpdateBrandRequest

| Field | Rules |
|-------|-------|
| `name` | `required`, `string`, `min:2`, `max:255`, `unique:brands,name,{brand_id},id,country_id,{admin_country_id},deleted_at,NULL` |

*(The uniqueness rule ignores the current record's own ID to allow saving without changing the name.)*

---

## State Transitions

```
[New] ──── storeBrand() ────► [Active]
                                  │
                        editBrand()│
                                  │
                             [Active] (name updated)
                                  │
                        deleteBrand()
                                  │
                             [Soft Deleted]  ←── hidden from all admin views
                                  │
                     (No restore path in this feature)
```

---

## Relationships Summary

```
Country
  ├── hasMany → Brand    (country_id on brands)
  └── hasMany → Admin    (country_id on admins)

Admin
  └── belongsTo → Country

Brand
  └── belongsTo → Country
```

---

## Migration Order (dependency order)

1. `create_countries_table` — no dependencies
2. `add_country_id_to_admins_table` — depends on countries
3. `create_brands_table` — depends on countries
