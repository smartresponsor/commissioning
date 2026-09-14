# Schema Runtime Checklist

## Entity-first development commands

For local development, after `composer install`:

```bash
php bin/console cache:clear
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:validate
php bin/console doctrine:schema:update --force
php bin/console commissioning:dev:seed
```

## Notes

- Migrations are intentionally deferred during entity-first construction.
- Entities are the active schema source during this phase.
- Use SQLite dev DB by default through `.env`.
- For PostgreSQL host integration, point `DATABASE_URL` to the host app database and keep `commission_` table prefix.
