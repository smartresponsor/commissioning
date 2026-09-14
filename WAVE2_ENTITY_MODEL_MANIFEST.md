# Wave 2 Entity Model Manifest

Wave 2 strengthens the entity-first Commissioning model.

## Added business entities

- `CommissionRuleEntity`
- `CommissionTierEntity`
- `CommissionBeneficiaryEntity`
- `CommissionCalculationLineEntity`
- `CommissionSettlementBatchEntryEntity`

## Purpose

The model now separates:

- plan definition
- rate definition
- rule conditions
- tier thresholds
- beneficiary ownership
- calculation header
- calculation lines
- ledger entries
- settlement batch membership

## Entity-first rule

Doctrine entities remain the source of truth for the active schema. Migrations are intentionally deferred during this development milestone.
