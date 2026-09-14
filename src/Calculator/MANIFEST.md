# src/Calculator

Calculation classes that compute commission amounts.

Rules:
- Classes use `Commission` prefix and `Calculator` suffix.
- Interfaces live in `src/CalculatorInterface`.
- Calculators are Symfony services.
- Calculators return DTOs and do not persist entities directly.
