# Commissioning Boundary Contract

## Allowed inbound references

Commissioning accepts scalar identifiers from host or sibling components:

- `order_reference`
- `payment_reference`
- `partner_reference`
- `affiliate_reference`
- `referral_reference`
- `marketplace_seller_reference`
- `subscription_reference`
- `pricing_reference`
- `currency_code`

## Forbidden inbound coupling

Commissioning must not import:

- Ordering entities
- Paying entities
- Payouting entities
- Taxating entities
- Pricing entities
- Currencing entities
- Subscriptioning entities
- host app Doctrine entities

## Outbound handoff

Commissioning exports:

- calculation result DTOs
- ledger status DTOs
- settlement batch DTOs
- settlement entry DTOs

It does not execute payout or payment capture.
