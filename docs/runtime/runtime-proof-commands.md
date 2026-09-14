# Commissioning Runtime Proof Commands

## Suggested order

```bash
php bin/console cache:clear
php bin/console commissioning:routes:audit
php bin/console commissioning:schema:readiness
php bin/console commissioning:runtime:audit
php bin/console commissioning:runtime:report
```

## Interpreting failures

### Missing route

Check `config/routes.yaml` and route imports.

### Missing service

Check `config/services.yaml`, aliases, and bundle/service imports in the host app.

### Missing Doctrine metadata

Check Doctrine mapping:

```yaml
doctrine:
  orm:
    mappings:
      Commissioning:
        is_bundle: false
        dir: '%kernel.project_dir%/src/Entity'
        prefix: 'App\Commissioning\Entity'
        alias: Commissioning
```

For host apps, adjust `dir` to vendor/path package location.
