# Controller / Route Inventory

## API controllers

| Controller | Route | Method |
|---|---|---|
| `CommissionApiCalculationController::preview` | `/api/commissioning/calculation/preview` | POST |
| `CommissionApiCalculationController::record` | `/api/commissioning/calculation` | POST |
| `CommissionApiSettlementController::markSettlementReady` | `/api/commissioning/ledger/settlement/ready` | POST |
| `CommissionApiSettlementController::createBatch` | `/api/commissioning/settlement/batch` | POST |
| `CommissionApiSettlementController::exportBatch` | `/api/commissioning/settlement/batch/export/{batchReference}` | GET |

## Preview/demo controllers still present

| Controller | Route | Method |
|---|---|---|
| `CommissionCalculationController::preview` | `/commissioning/calculation/preview` | GET |
| `CommissionEconomicEventCalculationController::preview` | `/commissioning/economic-event/preview` | GET |
| `CommissionEconomicEventRecordController::recordPreview` | `/commissioning/economic-event/record-preview` | GET/POST |
| `CommissionSettlementBatchController::createPreview` | `/commissioning/settlement-batch/create-preview` | GET/POST |
| `CommissionSettlementBatchController::exportPreview` | `/commissioning/settlement-batch/export-preview` | GET |

## Recommendation

For RC hardening, either keep preview routes under dev-only routing or mark them as demo routes in route docs.

## Wave 18 classification

- `/api/commissioning/...` routes are public API surface.
- `/commissioning/...` routes are demo/preview surface and should remain dev/test-only until retirement.
