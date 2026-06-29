# API & Routing Documentation

This document describes the key routes and HTTP endpoints registered under the `admin` prefix.

## Authentication Endpoints

| Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `admin/login` | `AuthController@loginView` | Render login form |
| `POST` | `admin/login` | `AuthController@login` | Process login request (AJAX) |
| `GET` | `admin/logout` | `AuthController@logout` | Log out and redirect |

## Admin Management Endpoints

| Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `admin/admins` | `AdminController@index` | List admins / Yajra JSON source |
| `GET` | `admin/admins/create` | `AdminController@create` | Render creation modal form |
| `POST` | `admin/admins` | `AdminController@store` | Store a new admin |
| `GET` | `admin/admins/{admin}/edit` | `AdminController@edit` | Render edit modal form |
| `PUT/PATCH` | `admin/admins/{admin}` | `AdminController@update` | Update admin details |
| `DELETE` | `admin/admins/{admin}` | `AdminController@destroy` | Delete admin |
| `POST` | `admin/multi_delete_admins` | `AdminController@multiDelete` | Delete multiple admins |

## Admin Profile Endpoints

| Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `admin/admin_profile` | `AdminController@profile` | Render admin profile page |
| `POST` | `admin/update-profile` | `AdminController@update_profile` | Update logged-in admin profile |
