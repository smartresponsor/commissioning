# Pre-Runtime Compatibility Notes

## Composer

Wave 19 changes Doctrine ORM requirement from `^4.0` to `^3.5` because stable projects commonly cannot resolve ORM 4 unless minimum stability allows dev packages. This keeps the component Symfony 8 oriented while reducing dependency-resolution risk.

## Serializer

Wave 19 explicitly requires:

- `symfony/property-access`
- `symfony/property-info`

These help Symfony Serializer denormalize constructor DTOs and inspect payload properties.

## Bundle extension

Wave 19 makes the extension alias explicit as `commissioning` and returns the extension from `CommissioningBundle`.

## Runtime audit visibility

The primary service interface aliases are marked public so the read-only runtime audit command can check their availability through the container.
