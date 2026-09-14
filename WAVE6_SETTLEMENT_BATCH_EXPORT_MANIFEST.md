# Wave 6 Settlement Batch Export Manifest

Wave 6 adds batch-oriented settlement export.

## Added responsibilities

- create Commissioning-owned settlement batches
- attach settlement-ready ledger entries to a batch
- export settlement batch entries as DTO data for Paying/Payouting
- keep payout execution out of Commissioning

## Boundary rule

Commissioning produces settlement instructions. Paying/Payouting executes money movement.
