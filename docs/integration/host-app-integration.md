# Host App Integration

## Composer path repository during development

In host `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../Commissioning",
      "options": {
        "symlink": true
      }
    }
  ],
  "require": {
    "commissioning/commission": "*"
  }
}
```

Then:

```bash
composer require commissioning/commission:* --prefer-source
```

## Bundle registration

If auto-discovery is not available, register:

```php
// config/bundles.php
return [
    App\Commissioning\CommissioningBundle::class => ['all' => true],
];
```

## Doctrine mapping: vendor install

```yaml
doctrine:
  orm:
    mappings:
      Commissioning:
        is_bundle: false
        dir: '%kernel.project_dir%/vendor/commissioning/commission/src/Entity'
        prefix: 'App\Commissioning\Entity'
        alias: Commissioning
```

## Doctrine mapping: local sibling path

```yaml
doctrine:
  orm:
    mappings:
      Commissioning:
        is_bundle: false
        dir: '%kernel.project_dir%/../Commissioning/src/Entity'
        prefix: 'App\Commissioning\Entity'
        alias: Commissioning
```

## Services

If services are not auto-loaded from vendor/path package, import them explicitly:

```yaml
imports:
  - { resource: '../vendor/commissioning/commission/config/services.yaml' }
```

For local path:

```yaml
imports:
  - { resource: '../../Commissioning/config/services.yaml' }
```
