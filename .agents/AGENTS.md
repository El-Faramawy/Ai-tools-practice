# Project Rules and Guidelines - Ai Tools Practice

This file outlines the coding style, framework conventions, helper functions, architecture, design patterns, and frontend integrations specific to this workspace.

---

## 1. Tech Stack & Environment
- **Backend Framework**: Laravel 12.0 (running on PHP 8.2+)
- **Frontend Assets**: Loaded statically via public assets under `public/Admin/` (no Vite, package.json, or node_modules)
- **Database**: MySQL (`ai_tools_practice`) for local development

---

## 2. Architecture & Directory Structure
This project utilizes a **Clean Architecture** approach with distinct layers of concern, separating routes, controllers, request validation, business logic (Services), and data access (Eloquent Models).

### Folder Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/               # Grouped admin controllers (resource or context-based)
│   ├── Middleware/              # Route authentication and check middlewares
│   └── Requests/
│       └── Admin/               # Grouped request validation classes (FormRequests)
├── Models/                      # Eloquent database models
├── Services/
│   └── Admin/                   # Business logic layers (Service classes)
└── helpers.php                  # Autoloaded custom helper functions
bootstrap/                       # App configurations (providers, middlewares)
config/                          # Global configuration files (auth, database, etc.)
database/
├── migrations/                  # Schema definition scripts
└── seeders/                     # Initial seed scripts
public/
└── Admin/                       # Static frontend assets (CSS, JS, plugins)
resources/
└── views/
    ├── Admin/                   # Blade templates for admin interfaces
    └── layouts/admin/           # Dashboard layouts and modular partials
routes/
└── web.php                      # Application routing
```

### Architectural Layers
1. **Routing Layer**: Connects incoming URLs to appropriate controllers and methods inside `routes/web.php`.
2. **Request Validation Layer**: Uses Form Requests under `App\Http\Requests\Admin\` to separate validation from controllers.
3. **Controller Layer**: Handles requests, coordinates routing to services, and returns view pages or JSON responses. Avoid writing database queries, password hashing, or complex business logic here.
4. **Service Layer**: Extract all business logic, database queries, hashing, and third-party integrations into Service classes under `App\Services\`.
5. **Model/Data Layer**: Eloquent models representing the database tables.

---

## 3. Design Patterns & Best Practices
- **RESTful API / Routing**: Design controllers and routes around RESTful resource conventions (Index, Create, Store, Show, Edit, Update, Destroy).
- **Service Layer & Dependency Injection**:
  - Organize service classes under `App\Services\Admin\`.
  - Inject these classes automatically into Controller constructors or method signatures using Laravel's dependency resolution.
- **SOLID Principles**:
  - **Single Responsibility (SRP)**: Keep classes small and focused on a single task. Request handles validation, Service handles business logic, Controller handles HTTP response, and Model handles data definition.
  - **Dependency Inversion (DIP)**: Use dependency injection instead of hardcoding class instantiations inside controllers.
- **Clean Code Principles**:
  - Self-explanatory naming conventions (variables, functions, classes).
  - DRY (Don't Repeat Yourself).
  - Write validation messages and user alerts in Arabic.

---

## 4. Authentication & Guards
- **Guard Name**: `admin`
  - Utilizes the `App\Models\Admin` model.
  - Configured in [config/auth.php](file:///D:/xampp/htdocs/Code/Projects/Ai/config/auth.php).
- **Middleware**: `admin`
  - Managed by `App\Http\Middleware\AdminMiddleware`.
  - Registered inside [bootstrap/app.php](file:///D:/xampp/htdocs/Code/Projects/Ai/bootstrap/app.php).
  - Routes requiring authentication must be grouped under this middleware.

---

## 5. Custom Helper Functions
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

## 6. AJAX Form & Table Integrations
- **Form Submission**: Add the CSS class `my_form` to forms to use the automatic AJAX-handling logic in [my-form.blade.php](file:///D:/xampp/htdocs/Code/Projects/Ai/resources/views/layouts/admin/inc/my-form.blade.php).
  - Successful responses should return JSON containing `message` and/or `url` for redirect.
  - Validation failures should return a 422 HTTP code with errors in the standard format.
- **DataTable Actions**:
  - The edit trigger button should use `id="editBtn"` and `data-id="..."`.
  - The delete trigger button should use `class="delete"` and `data-id="..."`.

---

## 7. Dos and Don'ts

### Dos
- **Do** write validations in separate Request validation classes (`App\Http\Requests\Admin\`).
- **Do** utilize the Service Layer for all database queries and core business logic.
- **Do** use RESTful design patterns for routes and controllers.
- **Do** write user-facing validation errors and alert messages in Arabic.
- **Do** write clean, self-documenting code with clear variable and function names.

### Don'ts
- **Don't** add any new Composer packages or NPM dependencies without asking the user first.
- **Don't** write SQL queries or Eloquent model database operations directly inside controllers.
- **Don't** use inline stylesheets or inline scripts where possible; rely on the existing style architecture.
