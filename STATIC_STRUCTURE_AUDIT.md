# Static Structure Audit

## Canon checks

| Check | Status |
|---|---|
| Composer name is `commissioning/commission` | Pass |
| Namespace is `App\Commissioning` | Pass |
| No `/src/Domain` | Pass |
| No Port-and-Adapter directory naming | Pass |
| Interfaces mirrored into `*Interface` folders | Pass |
| Symfony type-oriented layers are used | Pass |
| Doctrine entities live under `src/Entity` | Pass |
| Table names use `commission_` prefix | Pass |
| Root manifests exist | Pass |
| Machine-readable component manifest exists | Pass |

## Type layers currently present

- `src/Calculator`
- `src/CalculatorInterface`
- `src/Command`
- `src/Controller`
- `src/DependencyInjection`
- `src/DTO`
- `src/Entity`
- `src/Enum`
- `src/Event`
- `src/Exception`
- `src/Listener`
- `src/Repository`
- `src/RepositoryInterface`
- `src/Resolver`
- `src/ResolverInterface`
- `src/Service`
- `src/ServiceInterface`
- `src/Subscriber`
- `src/ValueObject`

## Open audit items

- Confirm autowiring aliases after actual `cache:clear`.
- Confirm Doctrine enum mapping under selected Doctrine ORM/DBAL versions.
- Confirm serializer can deserialize readonly promoted constructor DTOs as expected.
- Confirm `config/services.yaml` import behavior when used as vendor/path dependency.
