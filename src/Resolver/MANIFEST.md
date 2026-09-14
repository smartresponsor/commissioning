# src/Resolver

Resolver classes for selecting rates, beneficiaries, plans, or attribution context.

Rules:
- Classes use `Commission` prefix and `Resolver` suffix.
- Interfaces live in `src/ResolverInterface`.
- Resolvers prepare business inputs; they do not execute payment or payout.
