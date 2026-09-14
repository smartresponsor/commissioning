# Known Pre-Runtime Fixes

## Fix 1: Doctrine ORM stability

Problem pattern:

```text
Root composer.json requires doctrine/orm ^4.0, found doctrine/orm[4.0.x-dev] but it does not match your minimum-stability.
```

Fix:

```json
"doctrine/orm": "^3.5"
```

## Fix 2: Serializer constructor DTO support

Problem pattern:

```text
Could not denormalize object of type ...
```

Fix:

Require serializer support packages:

```json
"symfony/property-access": "^8.0",
"symfony/property-info": "^8.0"
```

## Fix 3: Bundle extension alias

Problem pattern:

```text
There is no extension able to load the configuration for "commissioning"
```

Fix:

`CommissioningExtension::getAlias()` returns `commissioning`.

## Fix 4: Runtime audit service visibility

Problem pattern:

```text
ServiceInterface: missing
```

Fix:

Primary service interface aliases are public for runtime audit visibility.
