# Wave 17 API Smoke Pack Manifest

Wave 17 adds API smoke assets for manual and CI-oriented runtime verification.

## Added responsibilities

- JSON request fixtures
- expected response shape fixtures
- curl smoke script
- idempotency smoke sequence
- settlement smoke sequence
- machine-readable smoke manifest

## Safety

Smoke assets call public API endpoints. They do not delete data, drop schema, clear directories, or run destructive commands.
