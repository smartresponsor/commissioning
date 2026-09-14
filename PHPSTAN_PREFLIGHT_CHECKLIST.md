# PHPStan Preflight Checklist

Run:

```bash
composer phpstan
```

Expected next fix areas after real output:

- Doctrine repository generic annotations
- Serializer return type narrowing in `CommissionApiJsonRequestMappingService`
- array-shape refinements for settlement export entries
- command payload array typing
- service transaction callback generic return typing

Do not add a baseline until after reviewing actual output.
