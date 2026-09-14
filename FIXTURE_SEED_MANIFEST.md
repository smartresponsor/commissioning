# Fixture Seed Manifest

## Command

`commissioning:dev:seed`

## Seeded objects

- `CommissionPlanEntity`: `default`
- `CommissionRateEntity`: default USD percentage rate
- `CommissionRateEntity`: default USD tiered rate
- `CommissionTierEntity`: 0-99999 at 5%
- `CommissionTierEntity`: 100000+ at 8%
- `CommissionRuleEntity`: `commission_enabled == 1`

## Idempotent behavior

The seed service checks existing plans/rates/rules before creating default seed records.
