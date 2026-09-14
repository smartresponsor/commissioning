# Static Analysis Preflight Notes

## Wave 21 changes

- `CommissionRuntimeAuditService` no longer checks Symfony private services through `ContainerInterface::has`.
- Runtime audit now reports expected service contracts from configuration and performs real checks for routes and Doctrine metadata.
- Runtime report mapping closure is typed.
- Runtime DTO arrays use `list<>` PHPDoc.
- Rate tiers use a `list<array{...}>` PHPDoc.

## Why this matters

Symfony containers usually hide private services. A runtime audit based on `ContainerInterface::has()` can fail even when services are correctly wired. Route and Doctrine metadata checks remain concrete runtime checks.
