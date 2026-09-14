# Final RC Audit Memo

## Current state

Commissioning has reached a strong RC-prep state for a Symfony-first, entity-first, bundle-usable component.

## Canon compliance

- Composer package: `commissioning/commission`
- Namespace: `App\Commissioning`
- Table prefix: `commission_`
- No `/src/Domain`
- No Port-and-Adapter structure
- Interfaces mirrored into dedicated interface folders
- Symfony type-oriented layers
- Entity-first Doctrine schema source
- DTO/ValueObject/Enum oriented contracts

## Business surface

Commissioning owns:

- commission plans
- commission rates
- commission rules
- commission tiers
- attribution references
- calculation engine
- calculation recording
- ledger entries
- settlement readiness
- settlement batch export

Commissioning does not own:

- payment capture
- payout execution
- product pricing
- tax/VAT
- currency metadata
- order lifecycle
- subscription lifecycle

## Remaining proof work

The next stage must run actual runtime commands and fix concrete failures:

```bash
composer validate
composer install
php bin/console cache:clear
php bin/console commissioning:runtime:audit
php bin/console commissioning:schema:readiness
php bin/console doctrine:schema:validate
composer phpstan
composer test
```

## RC decision

Recommended label:

**Commissioning M3 RC-prep snapshot**

Not yet production-ready until runtime proof passes.
