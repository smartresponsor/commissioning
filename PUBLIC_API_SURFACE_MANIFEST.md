# Public API Surface Manifest

The canonical public API surface is under `/api/commissioning`.

## Public routes

| Route | Method | Controller |
|---|---:|---|
| `/api/commissioning/calculation/preview` | POST | `CommissionApiCalculationController::preview` |
| `/api/commissioning/calculation` | POST | `CommissionApiCalculationController::record` |
| `/api/commissioning/ledger/settlement/ready` | POST | `CommissionApiSettlementController::markSettlementReady` |
| `/api/commissioning/settlement/batch` | POST | `CommissionApiSettlementController::createBatch` |
| `/api/commissioning/settlement/batch/export/{batchReference}` | GET | `CommissionApiSettlementController::exportBatch` |

## Policy

Only `/api/commissioning/...` routes should be considered public integration surface for host apps.
