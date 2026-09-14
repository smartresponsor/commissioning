# Repository Preflight Notes

## Repository structure

Repositories extend `ServiceEntityRepository` and implement mirrored `RepositoryInterface` contracts.

## Runtime assumptions

- DoctrineBundle is installed.
- Doctrine mapping includes `App\Commissioning\Entity`.
- Entity repositories are discovered as Symfony services through `src/Repository`.

## First checks

```bash
php bin/console debug:container App\\Commissioning\\Repository
php bin/console doctrine:mapping:info
php bin/console commissioning:schema:readiness
```
