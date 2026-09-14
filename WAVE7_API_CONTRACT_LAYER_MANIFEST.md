# Wave 7 API Contract Layer Manifest

Wave 7 adds a cleaner API contract surface for Commissioning.

## Added responsibilities

- request DTOs for public API endpoints
- response DTOs for public API endpoints
- API controller endpoints with stable route names
- route manifest for machines and future OpenAPI generation
- request mapping service to keep controllers thin

## Boundary rule

Controllers expose Commissioning application contracts. They do not import neighboring component entities and do not execute payouts.
