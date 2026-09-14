# Wave 4 Resolution Layer Manifest

Wave 4 adds the first business resolution layer for Commissioning.

## Added responsibilities

- resolve active commission plan by plan code
- resolve rate input from plan/rate references
- resolve attribution input from external source references
- normalize economic event context without depending on Ordering, Paying, Referring, Partnering, or Marketplace components
- provide an orchestration service that turns an economic event DTO into a calculation result

## Boundary rule

Commissioning accepts external references as scalar identifiers and DTO values. It does not own neighboring components and does not import their entities.
