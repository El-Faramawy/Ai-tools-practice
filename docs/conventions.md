# Code Conventions Documentation

This document outlines key development conventions used across the project codebase.

## 1. AJAX & Form Submission
- **Forms**: Forms that require AJAX submission must include the class `my_form`.
- **AJAX Handler**: Handled globally in `my-form.blade.php`.
- **Response Format**: Controller success responses should return JSON containing `message` and/or `url` to redirect:
  ```json
  {
      "message": "تم تسجيل الدخول بنجاح",
      "url": "http://localhost/admin"
  }
  ```
- **Error Responses**: Validation failures should return a 422 HTTP status with standard JSON validation errors.

## 2. DataTable Integration
- **Edit Triggers**: Interactive edit buttons in DataTables should use `id="editBtn"` and a `data-id` attribute containing the record ID.
- **Delete Triggers**: Interactive delete buttons in DataTables should use `class="delete"` and a `data-id` attribute containing the record ID.

## 3. Custom Helpers (helpers.php)
Always use custom helper functions to keep code concise:
- `admin()`: Accesses the admin auth guard.
- `setting()`: Fetches global settings with fallbacks.
- `get_file($file)`: Resolves public file URLs safely.
- `tableAction($id, $edit, $delete)`: Builds HTML action buttons (edit and delete) for Yajra DataTable rows.

## 4. Request Validation
- **Location**: Define validation logic under FormRequests in `App\Http\Requests\Admin\`.
- **Localization**: Write validation feedback, error messages, and alerts in Arabic.
