# src/Command

Symfony console commands owned by Commissioning.

Rules:
- Classes use `Commission` prefix and `Command` suffix.
- Commands should delegate business work to services.
- Commands must not shell out to destructive repository cleanup operations.
