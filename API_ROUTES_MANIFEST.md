# API Routes Manifest

## Commissioning API Routes

| Route name | Method | Path | Responsibility |
|---|---:|---|---|
| `commissioning_api_calculation_preview` | POST | `/api/commissioning/calculation/preview` | Calculate commission without persistence |
| `commissioning_api_calculation_record` | POST | `/api/commissioning/calculation` | Calculate and persist commission |
| `commissioning_api_settlement_ready` | POST | `/api/commissioning/ledger/settlement/ready` | Mark beneficiary ledger entries settlement-ready |
| `commissioning_api_settlement_batch_create` | POST | `/api/commissioning/settlement/batch` | Create settlement batch from ready entries |
| `commissioning_api_settlement_batch_export` | GET | `/api/commissioning/settlement/batch/export/{batchReference}` | Export batch for Paying/Payouting |

## Contract notes

- Inputs are scalar references and DTO payloads.
- Outputs are DTO responses.
- No neighboring entities are imported.
- Payout execution is out of scope.
