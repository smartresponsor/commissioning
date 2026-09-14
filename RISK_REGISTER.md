# Commissioning Risk Register

## R1: Serializer DTO mapping

Risk: readonly promoted DTO constructors may require serializer configuration depending on Symfony Serializer behavior and installed normalizers.

Mitigation:
- Run API request smoke tests.
- Add explicit DTO factories if serializer behavior is insufficient.

## R2: Doctrine enum mapping

Risk: enum mapping support depends on ORM/DBAL version compatibility.

Mitigation:
- Run `doctrine:schema:validate`.
- Run schema create/update in SQLite and target DB.

## R3: Repository-backed fallback

Risk: fallback logic can hide missing seed data.

Mitigation:
- Keep `development_fallbacks` config explicit.
- In later RC wave, enforce repository-backed mode for non-dev envs.

## R4: Settlement batch duplicate handling

Risk: duplicate check is service/repository based, but database unique constraints are still the stronger final guard.

Mitigation:
- Keep unique constraints.
- Confirm schema generation creates them.
- Consider transaction boundary in final runtime hardening.

## R5: Transaction boundaries

Risk: calculation record writes calculation, lines, and ledger entry through multiple flushes.

Mitigation:
- Later wave should add transaction wrapper or unit-of-work flush strategy.

## R6: Preview routes

Risk: preview/demo routes may leak into production.

Mitigation:
- Gate preview routes by environment or move them into docs/dev controller namespace.

## R7: Repository flush inside transaction

Risk: repositories still flush individually while wrapped in a transaction.

Mitigation: acceptable for this hardening step; later optimization can introduce deferred flush/unit-of-work strategy after runtime proof.
