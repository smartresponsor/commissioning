# Final Runtime Runbook


## Wave 20 config check

After applying Wave 20, first run:

```bash
php bin/console lint:yaml config/services.yaml
php bin/console cache:clear
```

If host app imports this config, run the same commands in host context.


## Wave 22 service discovery check

After applying Wave 22:

```bash
php bin/console lint:yaml config/services.yaml
php bin/console cache:clear
```

The service discovery policy now excludes DTO/Entity/Enum/Event/Exception/ValueObject/Interface layers from auto-registration.


## Wave 23 Doctrine preflight

After applying Wave 23:

```bash
composer update symfony/doctrine-bridge doctrine/orm doctrine/dbal doctrine/doctrine-bundle
php bin/console doctrine:mapping:info
php bin/console commissioning:schema:readiness
php bin/console doctrine:schema:validate
```
