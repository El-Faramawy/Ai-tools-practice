<!--
  SYNC IMPACT REPORT
  ==================
  Version Change: (unfilled template) → 1.0.0
  Modified Principles: N/A (initial ratification — all principles are new)
  Added Sections:
    - Core Principles (5 principles)
    - Technology Stack Constraints
    - Development Workflow
    - Governance
  Removed Sections: N/A
  Templates Requiring Updates:
    - .specify/templates/plan-template.md ✅ Compatible — Constitution Check gate already present
    - .specify/templates/spec-template.md ✅ Compatible — Functional requirements align with RESTful/SRP principles
    - .specify/templates/tasks-template.md ✅ Compatible — Phase structure aligns with layered implementation order
  Follow-up TODOs: None — all placeholders resolved.
-->

# AI Tools Practice Constitution

## Core Principles

### I. Clean Architecture (NON-NEGOTIABLE)

Every feature MUST be implemented across clearly separated architectural layers:
Route → Request → Controller → Service → Model. No layer MUST directly bypass
another. Controllers MUST NOT contain database queries, password hashing, or
complex business logic. Models MUST NOT contain HTTP-response logic. Violations
MUST be explicitly justified and documented in the Complexity Tracking table of
the plan.

**Rationale**: Enforces maintainability, testability, and long-term scalability
of the admin panel as features grow in complexity.

### II. Service-Layer Supremacy

All database queries, Eloquent operations, third-party integrations, password
hashing, and core business logic MUST reside in Service classes located under
`App\Services\Admin\`. Controllers MUST delegate all data operations to injected
Services via Laravel's dependency injection container. Direct Eloquent calls
inside controllers are FORBIDDEN.

**Rationale**: Keeps controllers thin and testable, and prevents knowledge
leakage between the HTTP handling layer and the data layer.

### III. Validation Isolation

All input validation MUST be written in dedicated FormRequest classes under
`App\Http\Requests\Admin\`. Inline validation inside controllers (e.g.,
`$request->validate(...)`) is FORBIDDEN. Validation error messages MUST be
written in Arabic to match the target user interface language.

**Rationale**: Separates the validation concern from the HTTP dispatch concern
(SRP), improves reusability, and ensures consistent Arabic-language feedback.

### IV. RESTful Design

Controllers and routes MUST follow RESTful resource conventions, exposing
`index`, `create`, `store`, `show`, `edit`, `update`, and `destroy` methods
where applicable. Custom actions MUST be justified. All admin routes MUST be
grouped under the `admin` middleware defined in `bootstrap/app.php`.

**Rationale**: Predictable route and controller structure reduces cognitive load
and makes onboarding new contributors faster.

### V. Simplicity, DRY & Helper Usage

Code MUST NOT duplicate logic that is already provided by custom helpers
(`admin()`, `setting()`, `get_file()`, `tableAction()`). New Composer or NPM
packages MUST NOT be added without explicit user approval. Complexity MUST be
justified — YAGNI principles apply. User-facing alert and notification messages
MUST be written in Arabic.

**Rationale**: Keeps the codebase lean and coherent, prevents dependency bloat,
and maintains a consistent Arabic-language user experience.

## Technology Stack Constraints

- **Backend**: Laravel 12.x on PHP 8.2+. No framework downgrades without
  explicit approval.
- **Frontend**: Static assets served from `public/Admin/` (CSS, JS, plugins).
  No Vite, Node.js build pipeline, or package.json unless explicitly approved.
- **Database**: MySQL (`ai_tools_practice`). All schema changes MUST be made
  via Laravel migrations — direct DDL on the database is FORBIDDEN.
- **Auth Guard**: The `admin` guard backed by `App\Models\Admin` MUST be used
  for all admin-facing routes. The `web` guard MUST NOT be used for admin areas.
- **AJAX**: Form submissions MUST use the `.my_form` class for the centralized
  AJAX handling layer. DataTable actions MUST use `id="editBtn"` and
  `class="delete"` conventions to remain compatible with the existing JS layer.

## Development Workflow

- **Routing**: All admin routes MUST be registered in `routes/web.php` under
  the `admin` middleware group.
- **Code Style**: Follow clean-code naming conventions — self-documenting
  variable and function names; no abbreviations unless industry-standard.
- **Migrations First**: Database schema changes MUST be delivered as migrations
  before any Service or Model code that depends on them.
- **No Inline Styles/Scripts**: Blade views MUST NOT contain inline `<style>` or
  `<script>` blocks where existing asset architecture can be used instead.
- **Commit Hygiene**: Each logical unit of work (migration, service, controller,
  view) SHOULD be committed separately with a descriptive message.

## Governance

This constitution supersedes all ad-hoc development practices. Any deviation
from a principle requires:

1. An explicit written justification in the feature's `plan.md` Complexity
   Tracking table.
2. Approval from the project owner before implementation begins.
3. A follow-up refactor task logged in `tasks.md` if a temporary exception is
   granted.

Amendments to this constitution MUST follow semantic versioning:

- **MAJOR** — Removal or redefinition of an existing principle.
- **MINOR** — Addition of a new principle or material expansion of guidance.
- **PATCH** — Wording clarifications, typo fixes, non-semantic refinements.

All code reviews and AI-assisted implementation sessions MUST verify compliance
with these principles before considering a feature complete. The
`.agents/AGENTS.md` file serves as the runtime development guidance reference
and MUST remain in sync with this constitution.

**Version**: 1.0.0 | **Ratified**: 2026-07-01 | **Last Amended**: 2026-07-01
