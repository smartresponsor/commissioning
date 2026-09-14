# PHPStan / PHPUnit Notes

## PHPStan

Configured through `phpstan.neon`.

Recommended command:

```bash
composer phpstan
```

Expected likely findings before runtime proof:

- generic collection annotations may need refinement
- serializer return type casts may need narrowing
- repository array return types may need explicit phpdoc adjustments

Do not add a baseline file until after real analysis output is available.

## PHPUnit

Configured through `phpunit.dist.xml`.

Recommended command:

```bash
composer test
```

Current smoke tests are intentionally narrow:

- percentage calculator test
- API DTO scalar reference test

Next tests should cover:

- idempotent record behavior
- settlement batch duplicate behavior
- serializer validation failure response
- repository-backed resolver with seeded SQLite DB
