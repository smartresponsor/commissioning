# Entity Table Manifest

All Commissioning-owned tables use the `commission_` prefix.

| Entity | Table |
|---|---|
| `CommissionPlanEntity` | `commission_plan` |
| `CommissionRateEntity` | `commission_rate` |
| `CommissionRuleEntity` | `commission_rule` |
| `CommissionTierEntity` | `commission_tier` |
| `CommissionBeneficiaryEntity` | `commission_beneficiary` |
| `CommissionAttributionEntity` | `commission_attribution` |
| `CommissionCalculationEntity` | `commission_calculation` |
| `CommissionCalculationLineEntity` | `commission_calculation_line` |
| `CommissionLedgerEntryEntity` | `commission_ledger_entry` |
| `CommissionSettlementBatchEntity` | `commission_settlement_batch` |
| `CommissionSettlementBatchEntryEntity` | `commission_settlement_batch_entry` |

## Entity-first rule

Entities define the active schema during this construction phase. Migration history is intentionally deferred.
