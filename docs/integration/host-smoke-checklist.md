# Host Smoke Checklist

## Composer

- [ ] Host composer can resolve `commissioning/commission`.
- [ ] Host autoload includes `App\Commissioning\`.
- [ ] No duplicate class namespace collision exists.

## Symfony

- [ ] Bundle can be registered or services imported.
- [ ] `bin/console debug:container Commissioning` shows Commissioning services.
- [ ] `bin/console debug:router commissioning` shows Commissioning routes.
- [ ] `bin/console cache:clear` succeeds.

## Doctrine

- [ ] Doctrine mapping includes `App\Commissioning\Entity`.
- [ ] Tables use `commission_` prefix.
- [ ] `bin/console doctrine:schema:validate` can see Commissioning entities.
- [ ] Schema creation/update is performed from entities during development.

## API

- [ ] `POST /api/commissioning/calculation/preview` accepts JSON.
- [ ] `POST /api/commissioning/calculation` records once and returns duplicate on repeated event reference.
- [ ] `POST /api/commissioning/ledger/settlement/ready` marks pending entries.
- [ ] `POST /api/commissioning/settlement/batch` creates batch.
- [ ] `GET /api/commissioning/settlement/batch/export/{batchReference}` exports handoff DTO.
