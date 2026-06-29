# Database Documentation

This application uses SQLite (`database/database.sqlite`) for local development.

## Schema Tables

### 1. `admins`
Stores administrators details for the backend panel.
- `id` (PK)
- `name` (string, nullable)
- `email` (string, unique, nullable)
- `password` (string, nullable)
- `timestamps`

### 2. `settings`
Global system configurations.
- `id` (PK)
- `name` (string, nullable)
- `logo` (string, nullable)
- `fav_icon` (string, nullable)
- `timestamps`

### 3. `users`
Registered application users.
- `id` (PK)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `remember_token` (string, nullable)
- `timestamps`
