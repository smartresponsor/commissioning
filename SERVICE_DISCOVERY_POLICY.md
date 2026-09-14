# Service Discovery Policy

## Service classes

Symfony service discovery should include concrete runtime classes only:

- `Command`
- `Controller`
- `Repository`
- `Service`
- `Resolver`
- `Calculator`
- `Subscriber`
- `Listener`

## Non-service classes

These layers must not be auto-registered as services:

- `DTO`
- `Entity`
- `Enum`
- `Event`
- `Exception`
- `ValueObject`
- `*Interface`
- `DependencyInjection`
- `Kernel.php`

## Interface binding

Interfaces are bound only through explicit aliases.

## Why

DTOs and Value Objects commonly have scalar constructor arguments. Interfaces are not instantiable. Registering them through broad discovery can break container compilation.
