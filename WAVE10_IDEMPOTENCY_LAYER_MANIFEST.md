# Wave 10 Idempotency Layer Manifest

Wave 10 adds idempotency protection for Commissioning write workflows.

## Added responsibilities

- avoid duplicate calculation records for the same economic event reference
- avoid duplicate settlement batch membership for the same ledger entry
- expose idempotency value objects and result DTOs
- keep write workflows deterministic during repeated API calls

## Canon

Idempotency is implemented in Symfony service/repository workflows, not through migration-led assumptions.
