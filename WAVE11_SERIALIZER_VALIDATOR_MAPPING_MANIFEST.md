# Wave 11 Serializer + Validator Mapping Manifest

Wave 11 replaces placeholder API DTO construction in controllers with Symfony-first request mapping.

## Added responsibilities

- decode JSON request body
- denormalize request payload into API DTOs
- validate DTOs through Symfony Validator
- return stable mapping exceptions for invalid input
- keep controllers thin and contract-oriented

## Canon

This is a Symfony-oriented API input layer. It does not introduce Port-and-Adapter structure and does not import neighboring component entities.
