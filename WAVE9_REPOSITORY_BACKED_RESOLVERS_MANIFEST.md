# Wave 9 Repository-Backed Resolvers Manifest

Wave 9 starts M2: Repository-Backed Runtime RC.

## Added responsibilities

- resolve commission plans from `CommissionPlanRepositoryInterface`
- resolve commission rates from `CommissionRateRepositoryInterface`
- resolve active commission rules from `CommissionRuleRepositoryInterface`
- keep deterministic fallback behavior explicit for development fixtures
- prepare repository-backed runtime without introducing migrations

## Boundary rule

Resolvers still consume scalar references and DTO context. They do not import neighboring component entities.
