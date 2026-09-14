# Commissioning entity-first migration retirement

## Scope

This patch converts the Commissioning component away from schema-first migration ownership and back to entity-first ownership.

## Retired schema-first source

- `Commissioning/migrations/**`

The retired migration created the fresh schema for:

- `commission_plan`
- `commission_beneficiary`
- `commission_attribution`
- `commission_settlement_batch`
- `commission_calculation`
- `commission_rate`
- `commission_rule`
- `commission_tier`
- `commission_ledger_entry`
- `commission_calculation_line`
- `commission_settlement_batch_entry`

Those tables are already represented by existing Doctrine entities in `src/Entity`.

## Old monolith transfer

The old monolith `Entity/Commission` contributed business concepts that were missing from the new Commissioning component:

- `CommissionEntity`
- `CommissionTypeEntity`
- `CommissionDirectionEntity`

The old `Commission` class had direct dependencies on Vendor/Product/Order interfaces. In the new component these are stored as boundary references:

- `vendorReference`
- `productReference`
- `orderReference`

This keeps Commissioning decoupled while preserving the business relationship model.

## Objecting adoption

New restored entities use Objecting embeddable traits for generic platform fields:

- `ObjectIdentityEmbeddableTrait`
- `ObjectAuditEmbeddableTrait`
- `ObjectStateEmbeddableTrait`

No local legacy `ObjectTrait` / `ObjectAuditTrait` copies were introduced.

## Notes

Existing runtime entities were not renamed or replaced because controllers, services, fixtures, calculators, and repositories already depend on them. The patch adds only the missing business model layer and retires the migration source.
