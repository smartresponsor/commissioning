# Commissioning API Smoke Pack

## Prerequisites

Run runtime setup first:

```bash
composer install
php bin/console cache:clear
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
php bin/console commissioning:dev:seed
symfony server:start
```

## Smoke sequence

Use:

```bash
bash scripts/smoke/commissioning_api_smoke.sh
```

Or run the curl commands from `docs/smoke/curl-commands.md`.

## Intent

This smoke pack verifies:

- API JSON request mapping
- calculation preview
- calculation record
- idempotency duplicate response
- settlement-ready transition
- settlement batch creation
- settlement batch export
