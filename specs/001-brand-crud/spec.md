# Feature Specification: Brand CRUD (Country-Scoped)

**Feature Branch**: `001-brand-crud`

**Created**: 2026-07-01

**Status**: Draft

**Input**: User description: "Add brand crud in controllers with clean arch, each admin can show and edit and add brands only in his country, brand has name, uuid, soft delete, timestamps, country_id"

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 — Browse My Country's Brands (Priority: P1)

An admin logs into the dashboard and navigates to the Brands section. The system
displays a paginated, searchable DataTable listing **only** the brands that belong
to the admin's assigned country. Brands from other countries are never visible.

**Why this priority**: This is the entry point for every brand-related operation.
Without a scoped brand listing, no other brand action (create, edit, delete) is
safe to expose.

**Independent Test**: A logged-in admin can visit the Brands index page and see
a table of brands; a brand belonging to a different country does NOT appear in
the table.

**Acceptance Scenarios**:

1. **Given** an admin is logged in and has a country assigned,
   **When** they navigate to the Brands index page,
   **Then** the DataTable lists only brands where `country_id` matches the admin's
   country, sorted by creation date (newest first).

2. **Given** a brand belongs to a different country,
   **When** any admin from another country accesses the Brands index,
   **Then** that brand does NOT appear in their listing.

3. **Given** no brands exist for the admin's country,
   **When** they visit the Brands index,
   **Then** an empty-state message is shown (in Arabic).

---

### User Story 2 — Create a New Brand (Priority: P2)

An admin fills in the brand creation form and submits it. The system validates the
input, creates the brand record assigned to the admin's country, and redirects
the admin to the Brands index with a success notification.

**Why this priority**: Creating brands is the primary write operation; it depends
on the scoped listing (US1) being in place first.

**Independent Test**: An admin can submit the Create Brand form and a new brand
record appears in their country-scoped listing.

**Acceptance Scenarios**:

1. **Given** a valid brand name is submitted,
   **When** the admin saves the form,
   **Then** a new brand is created with a system-generated UUID, `country_id` set
   to the admin's country, `soft_delete` not set, and correct timestamps; the admin
   is redirected to the index with an Arabic success message.

2. **Given** the brand name field is empty,
   **When** the admin submits the form,
   **Then** an Arabic validation error is displayed and no record is created.

3. **Given** a duplicate brand name within the same country,
   **When** the admin submits the form,
   **Then** an Arabic validation error is shown and no duplicate is stored.

---

### User Story 3 — Edit an Existing Brand (Priority: P3)

An admin clicks the edit button on a brand row. The system loads the edit form
pre-populated with current values. After submitting valid changes, the brand
record is updated and the admin is returned to the index.

**Why this priority**: Editing refines existing data; it builds on the list and
create flows.

**Independent Test**: An admin can open the edit form for one of their country's
brands, change the name, save, and see the updated value in the listing.

**Acceptance Scenarios**:

1. **Given** an admin clicks Edit on a brand that belongs to their country,
   **When** the edit form opens,
   **Then** it is pre-populated with the brand's current name.

2. **Given** an admin submits a valid updated brand name,
   **When** the form is saved,
   **Then** the brand record is updated and the admin sees an Arabic success message.

3. **Given** an admin attempts to edit a brand that belongs to a different country
   (e.g., by crafting a direct URL with a foreign brand UUID),
   **When** the system processes the request,
   **Then** the action is rejected with a 403 Forbidden response.

---

### User Story 4 — Soft-Delete a Brand (Priority: P4)

An admin clicks the Delete button on a brand row. The system performs a soft
delete (sets `deleted_at`), removes the brand from the active listing, and shows
an Arabic confirmation message. The record remains in the database for audit
purposes.

**Why this priority**: Deletion is a destructive action and should be available
after listing, create, and edit flows are stable.

**Independent Test**: An admin deletes a brand from their country; the brand
disappears from the active listing but its database record still has a
`deleted_at` timestamp.

**Acceptance Scenarios**:

1. **Given** an admin clicks Delete on one of their country's brands,
   **When** the existing JS confirmation dialog is accepted,
   **Then** the brand's `deleted_at` is set, it is removed from the index listing
   via the AJAX delete handler, and an Arabic success message is shown.

2. **Given** an admin clicks Delete but then cancels the confirmation dialog,
   **When** the dialog is dismissed,
   **Then** no request is sent and the brand record remains unchanged.

3. **Given** an admin attempts to delete a brand from a different country,
   **When** the system processes the request,
   **Then** the action is rejected with a 403 Forbidden response.

---

### Edge Cases

- What happens when an admin has no country assigned? → Access to the Brands
  section MUST be blocked; an Arabic error message is displayed.
- What happens if a UUID in a URL is syntactically invalid? → Return a 404
  response; do not expose database structure in the error.
- What if the brand name contains special characters or is excessively long? →
  Brand name MUST be between 2 and 255 characters; values outside this range
  are rejected with an Arabic validation error.

---

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: The system MUST display only brands whose `country_id` matches the
  currently authenticated admin's country.
- **FR-002**: Admins MUST be able to create brands; each brand MUST be
  automatically assigned the admin's `country_id` and a system-generated UUID.
- **FR-003**: Admins MUST be able to edit the name of brands within their country.
  The updated name MUST satisfy the same length constraints as creation (2–255 characters).
- **FR-004**: Admins MUST be able to soft-delete brands within their country.
  Deletion MUST be confirmed via the project's existing JS confirmation dialog
  before any request is sent; cancelling the dialog MUST result in no action.
- **FR-005**: The system MUST reject (403) any attempt by an admin to view, edit,
  or delete a brand that does not belong to their country.
- **FR-006**: Brands MUST NOT be permanently deleted from the database; only soft
  deletion (via `deleted_at`) is permitted through the UI. Restoring
  (un-deleting) a soft-deleted brand is explicitly OUT OF SCOPE for this feature;
  soft-deleted brands are hidden from all admin views with no recovery path in
  this release.
- **FR-007**: All validation error messages and user-facing notifications MUST be
  written in Arabic.
- **FR-008**: Brand name MUST be unique within the same country (duplicate names
  in the same country are rejected).
- **FR-011**: Brand name MUST be between 2 and 255 characters in length;
  names shorter than 2 or longer than 255 characters MUST be rejected with an
  Arabic validation error.
- **FR-009**: The Brands index page MUST present data via a DataTable supporting
  pagination and search.
- **FR-010**: UUID generation for new brands MUST be handled at the system level
  (not user-supplied).

### Key Entities

- **Brand**: Represents a product brand. Attributes: `id` (auto-increment PK),
  `uuid` (system-generated, unique), `name` (string, required), `country_id`
  (FK → countries), `deleted_at` (soft delete), `created_at`, `updated_at`.
- **Admin**: The authenticated user. Attributes relevant here: `country_id` (FK
  → countries). Determines which brands the admin can interact with.
- **Country**: Reference entity. Attributes: `id`. Links admins to their brands.

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: An admin can browse, create, edit, and soft-delete brands within
  their country without encountering any errors under normal usage.
- **SC-002**: 100% of attempts to access or modify brands outside the admin's
  country are blocked by the system before any data change occurs.
- **SC-003**: Brand creation and edit operations complete and return the admin to
  the index within a single form submission (no multi-step wizards needed).
- **SC-004**: All user-facing error and success messages are displayed in Arabic.
- **SC-005**: The Brands index DataTable loads and displays country-scoped results
  without requiring a full page reload.
- **SC-006**: No brand record is permanently removed from the database through any
  supported UI action; soft deletion is the only removal mechanism.

---

## Assumptions

- The `admins` table already has a `country_id` column; the relationship between
  `Admin` and `Country` is already established.
- A `countries` table and its corresponding Eloquent model already exist in the
  project.
- The admin authentication guard (`admin`) and the `AdminMiddleware` are already
  in place; this feature does not modify authentication logic.
- The dashboard already includes the Yajra DataTables JS/CSS assets; the Brands
  table will reuse the existing DataTable infrastructure.
- UUID generation is handled by Laravel's built-in `Str::uuid()` in the Service
  layer; no additional package is required.
- The admin panel already has a layout and navigation structure; the Brands
  section will be added as a new nav item within the existing layout.
- Mobile responsiveness for the admin panel is out of scope for this feature.
- Restoring (un-deleting) soft-deleted brands is out of scope for this feature;
  no trash view, archive listing, or restore action will be implemented.

---

## Clarifications

### Session 2026-07-01

- Q: How should brand deletion be confirmed before the soft-delete is executed? → A: Use the existing project JS confirmation dialog (SweetAlert / browser confirm — the established admin panel convention); cancelling the dialog sends no request.
- Q: What is the minimum allowed character count for a brand name? → A: 2 characters minimum (max 255).
- Q: Is brand restoration (un-deleting a soft-deleted brand) in scope for this feature? → A: Out of scope — soft-deleted brands are hidden permanently from the admin UI; no restore action in this release.
