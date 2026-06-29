# Project Rules and Guidelines - Ai Tools Practice

This file outlines the coding style, framework conventions, helper functions, and frontend integrations specific to this workspace.

---

## 1. Tech Stack & Environment
- **Backend Framework**: Laravel 12.0 (running on PHP 8.2+)
- **Frontend Assets**: Loaded statically via public assets under `public/Admin/` (no Vite, package.json, or node_modules)
- **Database**: MySQL (`ai_tools_practice`) for local development

---

## 2. Authentication & Guards
- **Guard Name**: `admin`
  - Utilizes the `App\Models\Admin` model.
  - Configured in [config/auth.php](file:///D:/xampp/htdocs/Code/Projects/Ai/config/auth.php).
- **Middleware**: `admin`
  - Managed by `App\Http\Middleware\AdminMiddleware`.
  - Registered inside [bootstrap/app.php](file:///D:/xampp/htdocs/Code/Projects/Ai/bootstrap/app.php).
  - Routes requiring authentication must be grouped under this middleware.

---

## 3. Custom Helper Functions
Helper functions are autoloaded via Composer from [app/helpers.php](file:///D:/xampp/htdocs/Code/Projects/Ai/app/helpers.php). Always leverage these helpers:

- **`admin()`**
  - Returns the `admin` guard instance.
  - *Usage*: `admin()->user()->id` or `admin()->id()`.
- **`setting()`**
  - Retrieves the global `App\Models\Setting` model.
  - Returns fallback values if no setting is defined yet.
  - *Usage*: `setting()->name` or `setting()->logo`.
- **`get_file($file)`**
  - Returns the URL asset of the provided path, with a default image fallback if file is empty or missing.
  - *Usage*: `get_file(setting()->logo)`.
- **`tableAction($id, $edit = true, $delete = true)`**
  - Generates HTML action buttons (edit and delete) for Yajra DataTables.
  - Uses Feather icons matching the dashboard theme.

---

## 4. Controller & Request Validation
- **Form Validation**: Always organize request classes under `App\Http\Requests\Admin\`.
  - Admin management requests: `Admin\StoreAdminRequest` and `Admin\UpdateAdminRequest`.
  - Profile update requests: `Profile\UpdateProfileRequest`.
- Validation errors and alerts should be in Arabic to align with the application interface.

---

## 5. AJAX Form & Table Integrations
- **Form Submission**: Add the CSS class `my_form` to forms to use the automatic AJAX-handling logic in [my-form.blade.php](file:///D:/xampp/htdocs/Code/Projects/Ai/resources/views/layouts/admin/inc/my-form.blade.php).
  - Successful responses should return JSON containing `message` and/or `url` for redirect.
  - Validation failures should return a 422 HTTP code with errors in the standard format.
- **DataTable Actions**:
  - The edit trigger button should use `id="editBtn"` and `data-id="..."`.
  - The delete trigger button should use `class="delete"` and `data-id="..."`.

---

## 6. Service Layer & Dependency Injection Pattern
For clean separation of concerns and testability, business logic should be extracted from controllers into Service classes:
- **Location**: Organize services in directories under `App\Services\`, grouped by resource or context (e.g. `App\Services\Admin\AdminService`).
- **Dependency Injection**: Always inject service classes into Controller constructors or method signatures using Laravel's automatic dependency resolution (type-hinting).
- **Controller Role**: Controllers should focus only on request validation, calling the appropriate Service method, and returning HTTP/JSON responses.
- **Service Role**: Services handle all database queries, logic, hashing, data manipulation, and interactions with external resources.

