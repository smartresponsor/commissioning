# Doctrine Preflight Notes

## UUID fields

Commissioning entities use Symfony `Uuid` with Doctrine column type `uuid`.

Composer now explicitly requires:

```json
"symfony/doctrine-bridge": "^8.0"
```

This improves compatibility for Symfony UID Doctrine bridge types.

## Doctrine checks

Run:

```bash
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
php bin/console commissioning:schema:readiness
```

## Expected mapped entities

- `CommissionPlanEntity`
- `CommissionRateEntity`
- `CommissionRuleEntity`
- `CommissionTierEntity`
- `CommissionBeneficiaryEntity`
- `CommissionAttributionEntity`
- `CommissionCalculationEntity`
- `CommissionCalculationLineEntity`
- `CommissionLedgerEntryEntity`
- `CommissionSettlementBatchEntity`
- `CommissionSettlementBatchEntryEntity`

## If uuid type fails

Typical symptom:

```text
Unknown column type "uuid" requested
```

First verify `symfony/doctrine-bridge` is installed and Symfony bundles are loaded.
