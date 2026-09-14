# Agent Instructions: Commissioning

This repository is a Symfony-first Commissioning component.

## Non-negotiable rules

1. Use namespace `App\Commissioning`.
2. Use composer name `commissioning/commission`.
3. Do not introduce `/src/Domain`.
4. Do not introduce Port-and-Adapter structure.
5. Do not place interfaces beside implementations.
6. Mirror interfaces into dedicated interface layers:
   - `src/ServiceInterface/...`
   - `src/RepositoryInterface/...`
   - `src/CalculatorInterface/...`
   - `src/ResolverInterface/...`
7. Keep entity-first schema orientation.
8. Table names must start with `commission_`.
9. Classes must use `Commission` prefix and code-form suffix.
10. Do not add legacy compatibility branches during foundation phase.
11. Do not add migration history as the source of truth during early development.
12. Prefer explicit DTO, Value Object, Enum, and Entity contracts.
