# JengaMetrics API Migration Blueprint

## Goal

Move from mixed Blade + React page rendering to a stable API-first Laravel backend with a React frontend consuming versioned JSON APIs, while preserving existing project/role logic.

## Scope and Principles

- Keep existing web routes operational during migration.
- Build versioned APIs under `/api/v1`.
- Reuse existing Form Requests, Policies, and middleware rules where possible.
- Enforce project context uniformly for every API request.
- Migrate module-by-module behind feature flags.

## Target Architecture

- **Backend:** Laravel API (`/api/v1/...`), Sanctum auth, Resource transformers.
- **Frontend:** React app uses API client for dashboard + module pages.
- **Auth model:** Same-domain cookie-based session via Sanctum for SPA.
- **Authorization:** Centralized policy checks + role capability gates.
- **Project context:** Resolved once per request via dedicated service/middleware.

## Foundational Backend Work (Phase 0)

1. Add API route groups and versioning in `routes/api.php`.
2. Create `ApiResponse` envelope convention:
   - success: `{ "data": ..., "meta": ..., "message": ... }`
   - error: `{ "error": { "code": "...", "message": "...", "details": ... } }`
3. Add `ResolveActiveProject` middleware for API.
4. Add API Resources (`Http/Resources/*`) per aggregate.
5. Add API exception handler mapping (validation, auth, business conflict).
6. Add OpenAPI doc seed (`docs/openapi.yaml`).

## Auth and Permission Matrix

- **Roles**
  - `admin`
  - `user` (main account)
  - `sub-account`
- **Capability checks**
  - `boq` -> `can_manage_boq`
  - `materials` -> `can_manage_materials`
  - `labour` -> `can_manage_labour`
- **Enforcement order**
  1. authenticated user
  2. active project resolved
  3. role capability for module/action
  4. resource ownership/project binding

## API Endpoint Blueprint (v1)

### Auth + Session

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `GET /api/v1/auth/me`
- `GET /api/v1/projects/active`
- `POST /api/v1/projects/active` (switch active project)

### Dashboard

- `GET /api/v1/dashboard/summary`
- `GET /api/v1/dashboard/charts/costs`
- `GET /api/v1/dashboard/charts/progress`
- `GET /api/v1/dashboard/project-steps`
- `POST /api/v1/dashboard/project-steps`
- `PATCH /api/v1/dashboard/project-steps/{id}`

### BoQ

- `GET /api/v1/boq/documents`
- `POST /api/v1/boq/documents`
- `GET /api/v1/boq/documents/{id}`
- `PATCH /api/v1/boq/documents/{id}`
- `DELETE /api/v1/boq/documents/{id}`
- `POST /api/v1/boq/documents/{id}/copy`
- `GET /api/v1/boq/documents/{id}/levels`
- `POST /api/v1/boq/documents/{id}/levels`

### BoM

- `GET /api/v1/boms`
- `POST /api/v1/boms`
- `GET /api/v1/boms/{id}`
- `DELETE /api/v1/boms/{id}`
- `GET /api/v1/boms/report`

### Requisitions

- `GET /api/v1/requisitions`
- `POST /api/v1/requisitions`
- `POST /api/v1/requisitions/adhoc`
- `PATCH /api/v1/requisitions/{id}/approve`
- `PATCH /api/v1/requisitions/{id}/reject`
- `PATCH /api/v1/requisitions/{id}/status`

### Materials

- `GET /api/v1/materials`
- `GET /api/v1/materials/delivered`
- `GET /api/v1/materials/inventory`
- `GET /api/v1/materials/usage`
- `POST /api/v1/materials`
- `PATCH /api/v1/materials/{id}`
- `DELETE /api/v1/materials/{id}`
- `POST /api/v1/materials/{id}/use`

### Suppliers and Products

- `GET /api/v1/suppliers`
- `POST /api/v1/suppliers`
- `GET /api/v1/suppliers/search`
- `GET /api/v1/products`

### Labour and Payments

- `GET /api/v1/workers`
- `POST /api/v1/workers`
- `GET /api/v1/workers/{id}`
- `PATCH /api/v1/workers/{id}`
- `DELETE /api/v1/workers/{id}`
- `POST /api/v1/workers/{id}/restore`
- `GET /api/v1/workers/{id}/payments`
- `POST /api/v1/workers/{id}/payments`
- `GET /api/v1/attendance`
- `POST /api/v1/attendance`

### Finance and Reporting

- `GET /api/v1/cost-tracking`
- `GET /api/v1/reports/summary`
- `GET /api/v1/reports/wages`
- `GET /api/v1/reports/purchases`

## Canonical DTO Examples

### Worker Create Request

```json
{
  "full_name": "Jane Doe",
  "id_number": "12345678",
  "job_category": "Mason",
  "work_type": "Under Contract",
  "phone": "0712345678",
  "email": "jane@example.com",
  "payment_amount": 1200,
  "payment_frequency": "per day",
  "mode_of_payment": "Cash"
}
```

### Material Create (Adhoc) Request

```json
{
  "material_type": "adhoc",
  "adhoc_name": "Cement",
  "adhoc_unit": "bags",
  "unit_price": 250,
  "quantity_in_stock": 40,
  "supplier_id": 12
}
```

### Requisition Create (Adhoc) Request

```json
{
  "material_name": "Timber",
  "unit_of_measurement": "pcs",
  "quantity_requested": 12,
  "section": 53
}
```

## Frontend Migration Plan

1. Build shared API client (`resources/js/api/client.ts`).
2. Introduce typed query hooks per module.
3. Replace current dashboard mock data with API data first.
4. Migrate modules in this order:
   - BoQ
   - BoM
   - Materials/Requisitions
   - Workers/Payments
   - Cost Tracking + Reports
5. Remove module Blade views only after parity sign-off.

## Sprint Board (Execution Plan)

### Sprint 1 (Foundation)

- API version scaffolding + auth/session endpoints
- project context middleware/service
- API response envelope + error mapper
- OpenAPI seed
- CI checks for API routes + feature tests

### Sprint 2 (Read APIs)

- dashboard summary/charts APIs
- BoQ/BoM read APIs
- materials/requisitions/workers read APIs
- React consumes dashboard read APIs

### Sprint 3 (Write APIs: Materials + Labour)

- materials create/update/use
- requisitions create/approve/reject
- workers create/update/archive/restore
- payments create/list

### Sprint 4 (Write APIs: BoQ + BoM + Reporting)

- BoQ document + levels write APIs
- BoM generation and delete APIs
- cost-tracking + reports APIs
- React module pages switched to API data

### Sprint 5 (Cutover + Hardening)

- remove remaining mock client datasets
- feature-flag cutover by module
- load/perf pass on heavy report endpoints
- rollback playbook + production runbook

## Testing Strategy

- **API feature tests**
  - auth, permission, project scope, CRUD, conflict paths
- **Contract tests**
  - schema snapshots for all critical endpoints
- **E2E tests**
  - login -> create worker -> payment -> material -> requisition -> report
- **Observability**
  - capture endpoint latency p95, error rate, and redirects

## Risks and Controls

- **Risk:** Permission drift between web and API.
  - **Control:** Shared policy classes and request tests by role.
- **Risk:** Project scope inconsistencies.
  - **Control:** One project resolver middleware for all API endpoints.
- **Risk:** Partial frontend migration confusion.
  - **Control:** Feature flags and per-module rollout checklist.

## Definition of Done (Per Module)

- API read + write endpoints implemented.
- Validation, policy, and project scope tests passing.
- Frontend module uses API only (no mock data, no hidden server page dependency).
- E2E workflow green in staging.
- Rollback path documented.

