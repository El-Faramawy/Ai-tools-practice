# AJAX Contracts: Brand CRUD (Country-Scoped)

**Feature**: `001-brand-crud`
**Date**: 2026-07-01

This document defines the HTTP contracts between the admin panel's AJAX layer
and the Laravel backend for all Brand CRUD operations.

---

## Route Summary

All routes are prefixed with `/admin` and protected by the `admin` middleware.

| Method | URI | Route Name | Controller Action |
|--------|-----|------------|-------------------|
| GET | `/admin/brands` | `brands.index` | `BrandController@index` |
| GET | `/admin/brands/create` | `brands.create` | `BrandController@create` |
| POST | `/admin/brands` | `brands.store` | `BrandController@store` |
| GET | `/admin/brands/{uuid}/edit` | `brands.edit` | `BrandController@edit` |
| PUT/PATCH | `/admin/brands/{uuid}` | `brands.update` | `BrandController@update` |
| DELETE | `/admin/brands/{uuid}` | `brands.destroy` | `BrandController@destroy` |

> **Note**: `{uuid}` is the brand's UUID string (not integer ID). The service
> resolves the brand by UUID + country scope.

---

## Contracts

### 1. GET `/admin/brands` — Index (DataTable)

**When**: Called as AJAX (`$request->ajax()` is true) by the DataTables JS plugin.

**Request Headers**:
```
X-Requested-With: XMLHttpRequest
```

**Response** `200 OK`:
```json
{
  "draw": 1,
  "recordsTotal": 12,
  "recordsFiltered": 12,
  "data": [
    {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "name": "نايك",
      "created_at": "2026-07-01T02:00:00.000000Z",
      "action": "<button ... id=\"editBtn\" data-id=\"{uuid}\">...</button><button ... class=\"delete\" data-id=\"{uuid}\">...</button>",
      "DT_RowId": "row_1"
    }
  ]
}
```

> **Important**: The `action` column uses `tableAction($brand->uuid)` so the JS
> layer correctly passes the UUID (not integer ID) to edit/delete requests.

---

### 2. GET `/admin/brands/create` — Create Form (modal partial)

**When**: Triggered by the "Add" button click. The AJAX layer loads the partial
into the modal body.

**Response** `200 OK` — HTML fragment:
```html
<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('brands.store') }}">
  @csrf
  <!-- Brand name input field -->
</form>
```

---

### 3. POST `/admin/brands` — Store Brand

**Request Body** (`application/x-www-form-urlencoded`):
```
_token: {csrf_token}
name: "نايك"
```

**Success Response** `200 OK`:
```json
{
  "message": "تم الاضافة بنجاح"
}
```

**Validation Failure Response** `422 Unprocessable Entity`:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["اسم الماركة مطلوب"]
  }
}
```

> The `my_form` AJAX handler reads `message` on success and `errors` on 422 to
> display Arabic feedback.

---

### 4. GET `/admin/brands/{uuid}/edit` — Edit Form (modal partial)

**Response** `200 OK` — HTML fragment:
```html
<form id="form" method="POST" action="{{ route('brands.update', $brand->uuid) }}">
  @csrf
  @method('PUT')
  <!-- pre-populated name field -->
</form>
```

**Not Found / Cross-Country** `403 Forbidden` or `404 Not Found` (JSON if AJAX):
```json
{
  "message": "غير مصرح لك بهذا الإجراء"
}
```

---

### 5. PUT `/admin/brands/{uuid}` — Update Brand

**Request Body**:
```
_token: {csrf_token}
_method: PUT
name: "أديداس"
```

**Success Response** `200 OK`:
```json
{
  "message": "تم التعديل بنجاح"
}
```

**Validation Failure** `422 Unprocessable Entity`: same structure as store.

**Cross-Country Access** `403 Forbidden`:
```json
{
  "message": "غير مصرح لك بهذا الإجراء"
}
```

---

### 6. DELETE `/admin/brands/{uuid}` — Soft-Delete Brand

**Request** (triggered by JS delete handler after confirmation dialog):
```
DELETE /admin/brands/{uuid}
X-Requested-With: XMLHttpRequest
```

**Success Response** `200 OK`:
```json
{
  "message": "تم الحذف بنجاح"
}
```

**Cross-Country Access** `403 Forbidden`:
```json
{
  "message": "غير مصرح لك بهذا الإجراء"
}
```

---

## Error Handling Summary

| Scenario | HTTP Status | Response |
|----------|------------|----------|
| Validation fails (name empty/too short/too long/duplicate) | 422 | `{ "errors": { "name": [...] } }` |
| Brand UUID not found | 404 | Standard Laravel 404 |
| Brand belongs to different country | 403 | `{ "message": "غير مصرح لك بهذا الإجراء" }` |
| Admin has no country assigned | 403 | `{ "message": "غير مصرح لك بهذا الإجراء" }` |
| Unauthenticated | 401/redirect | Redirect to admin login |
