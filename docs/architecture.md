# Architecture Documentation

This project uses a Clean Architecture design to separate concerns and ensure maintainability.

## Architectural Layers

1. **Routing Layer (`routes/web.php`)**
   - Directs URL requests to appropriate controllers.
   - Organizes admin routes inside the `admin` prefix and `admin` middleware group.

2. **Request Validation Layer (`app/Http/Requests/Admin/`)**
   - Extracts input validation logic from controllers.
   - FormRequests authorize and validate incoming parameters.

3. **Controller Layer (`app/Http/Controllers/Admin/`)**
   - Coordinates the request lifecycle.
   - Calls service layers to process business logic.
   - Returns JSON responses for AJAX/DataTable requests or renders Blade views.

4. **Service Layer (`app/Services/Admin/`)**
   - Encapsulates core business logic, complex database queries, and third-party integrations.

5. **Model/Data Layer (`app/Models/`)**
   - Defines database entities and Eloquent relationships.
