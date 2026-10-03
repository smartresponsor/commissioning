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
8. Doctrine table ownership follows the canonical `commission_` profile exactly once: the root table may be `commission`, while additional owned tables use `commission_<semantic_qualifier>`; duplicated ownership tokens such as `commission_commission` are forbidden.
9. Classes must use `Commission` prefix and code-form suffix.
10. Do not add legacy compatibility branches during foundation phase.
11. Do not add migration history as the source of truth during early development.
12. Prefer explicit DTO, Value Object, Enum, and Entity contracts.
## Platform Canon Precedence

For work under `D:\PhpstormProjects\www`, authoritative platform rules live in the Canonization repository. Gating is the executable mirror for objectively guardable rules. This `AGENTS.md` is an agent-facing projection or local supplement and must not override or contradict Canonization.

If a local instruction conflicts with current Canonization, follow Canonization and synchronize this file. Local instructions may narrow scope or add repository-specific constraints only when they remain compatible with Canonization.
