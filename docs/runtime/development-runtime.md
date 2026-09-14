# Commissioning Development Runtime

## Setup

```bash
composer install
php bin/console cache:clear
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
php bin/console commissioning:dev:seed
```

## Validate

```bash
composer validate
composer cs:check
composer phpstan
composer test
php bin/console doctrine:schema:validate
```

## API smoke

After schema and seed:

```bash
curl -X POST http://localhost:8000/api/commissioning/calculation/preview \
  -H "Content-Type: application/json" \
  -d '{"eventReference":"order-1001","currencyCode":"USD","basisMinorAmount":10000,"planCode":"default","attributionSourceType":"partner","attributionSourceReference":"partner-1","context":{"commission_enabled":"1","commission_rate_type":"percentage","commission_percentage_rate":"10"}}'
```
