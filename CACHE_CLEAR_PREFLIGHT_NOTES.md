# Cache Clear Preflight Notes

## Wave 22 expected improvement

`php bin/console cache:clear` should no longer try to autowire:

- API request DTOs
- API response DTOs
- Value Objects
- Enums
- Interfaces
- Doctrine entities
- Exceptions

## First commands to run

```bash
php bin/console lint:yaml config/services.yaml
php bin/console cache:clear
php bin/console debug:container App\\Commissioning\\Service
php bin/console debug:router commissioning
```

## If cache clear still fails

Send the first concrete Symfony exception. The next fix wave should be based on that exact error.
