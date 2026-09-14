# Transaction Boundary Manifest

## Transaction runner

`CommissionTransactionServiceInterface`

Implementation:

`CommissionTransactionService`

## Transaction-wrapped workflows

- `CommissionCalculationRecordService::record`
- `CommissionSettlementBatchService::createBatch`

## Purpose

Avoid partial writes across multi-entity workflows:

- calculation header
- calculation lines
- ledger entry
- settlement batch
- settlement batch entry

## Runtime note

Actual transaction behavior must be proven against the selected DBAL/ORM runtime.
