# Demo Route Gating Manifest

## Demo routes

Demo/preview routes under `/commissioning/...` are guarded by `CommissionDemoRouteGuardService`.

Allowed environments:

- `dev`
- `test`

Forbidden environments:

- `prod`
- any other non-dev/non-test environment

## Public API routes

Routes under `/api/commissioning/...` are not demo routes and remain available for host/API use.
