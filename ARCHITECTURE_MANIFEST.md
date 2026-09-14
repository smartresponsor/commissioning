# Architecture Manifest

## Component

Commissioning owns commission business logic for marketplace, affiliate, reseller, referral, revenue split, and partner commission workflows.

## Symfony orientation

This is a Symfony application/package using default framework conventions, Doctrine entities, Symfony services, controllers, validators, serializers, and configuration.

## Source-tree canon

`src/` contains type-identifiable layers:

- `Controller`
- `DTO`
- `Entity`
- `Enum`
- `Event`
- `Listener`
- `Repository`
- `RepositoryInterface`
- `Service`
- `ServiceInterface`
- `Subscriber`
- `ValueObject`
- `DependencyInjection`
- `CommissioningBundle.php`

Interfaces are mirrored into dedicated interface folders.

## Entity-first model

Entities define the truth of the database schema during active construction. Migrations are deferred until schema direction stabilizes.

## Data ownership

All Doctrine tables owned by this component must start with `commission_`.
