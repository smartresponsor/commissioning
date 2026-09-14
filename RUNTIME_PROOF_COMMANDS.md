# Runtime Proof Commands

Wave 16 adds read-only runtime diagnostics.

## Commands

```bash
php bin/console commissioning:runtime:audit
php bin/console commissioning:runtime:report
php bin/console commissioning:routes:audit
php bin/console commissioning:schema:readiness
```

## Meaning

- `commissioning:runtime:audit`: human-readable aggregate runtime check.
- `commissioning:runtime:report`: JSON runtime report for machines/CI.
- `commissioning:routes:audit`: lists registered Commissioning routes.
- `commissioning:schema:readiness`: checks Doctrine metadata mapping for Commissioning entities.

## Safety

These commands are read-only. They do not mutate schema, filesystem, cache, or business data.
