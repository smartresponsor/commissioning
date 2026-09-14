# Wave 22 Service Discovery Hardening Manifest

Wave 22 hardens Symfony service discovery before actual `cache:clear`.

## Fix target

Broad `App\Commissioning\` service discovery can accidentally register classes that are not services:

- DTOs
- Entities
- Enums
- Events
- Exceptions
- Interfaces
- Value Objects
- DependencyInjection classes
- Kernel

This can cause Symfony autowiring/container compilation errors because many of these classes have scalar constructor arguments or are not instantiable.

## What this wave does

- narrows global service discovery with explicit excludes
- removes interface-directory resource registration
- keeps explicit aliases for all service contracts
- keeps calculator tagged iterator
- keeps controller service-argument discovery
- keeps command/service/repository concrete discovery

## Non-claim

This wave still does not claim `cache:clear` was executed. It removes a likely compile blocker.
