# Next Runtime Proof Checklist

Run these on the actual repository after all touched overlays are applied.

```bash
composer validate
composer install
php bin/console cache:clear
php bin/console debug:container Commissioning
php bin/console debug:router commissioning
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:validate
php bin/console doctrine:schema:update --force
php bin/console commissioning:dev:seed
composer cs:check
composer phpstan
composer test
```

## API smoke

```bash
symfony server:start
```

Then test:

- `POST /api/commissioning/calculation/preview`
- `POST /api/commissioning/calculation`
- repeated `POST /api/commissioning/calculation`
- `POST /api/commissioning/ledger/settlement/ready`
- `POST /api/commissioning/settlement/batch`
- `GET /api/commissioning/settlement/batch/export/{batchReference}`
```
