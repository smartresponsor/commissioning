# Idempotency Manifest

## Economic event recording

Duplicate key: `economicEventReference`

Behavior:

- if no calculation exists: create calculation, lines, ledger entry
- if calculation exists: return duplicate result and do not create new ledger entry

## Settlement batch membership

Duplicate key: `(batch, ledgerEntry)`

Behavior:

- if batch entry does not exist: create membership
- if batch entry exists: skip and increment duplicate count
