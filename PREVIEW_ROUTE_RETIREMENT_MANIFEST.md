# Preview Route Retirement Manifest

Preview/demo routes exist only for development and smoke exploration.

## Demo routes

| Route | Method | Status |
|---|---:|---|
| `/commissioning/calculation/preview` | GET | dev/test only |
| `/commissioning/economic-event/preview` | GET | dev/test only |
| `/commissioning/economic-event/record-preview` | GET/POST | dev/test only |
| `/commissioning/settlement-batch/create-preview` | GET/POST | dev/test only |
| `/commissioning/settlement-batch/export-preview` | GET | dev/test only |

## Current protection

All demo routes are guarded by `CommissionDemoRouteGuardService` and return 404 outside `dev`/`test`.

## Retirement plan

1. Keep demo routes during runtime proof.
2. Use API smoke pack as the canonical verification surface.
3. After API smoke is stable, either:
   - move demo controllers to a dev-only routing file, or
   - remove demo controllers in a touched-files cleanup wave.
4. Do not remove public API controllers.
