# Host App Integration Notes

## Symfony host

Recommended host integration direction:

```yaml
doctrine:
  orm:
    mappings:
      Commissioning:
        is_bundle: false
        dir: '%kernel.project_dir%/vendor/commissioning/commission/src/Entity'
        prefix: 'App\Commissioning\Entity'
        alias: Commissioning
```

If the component is path-mounted during development, point the `dir` to that local path.

## Service integration

Use service contracts:

- `CommissionEconomicEventCalculationServiceInterface`
- `CommissionEconomicEventRecordServiceInterface`
- `CommissionSettlementReadinessServiceInterface`
- `CommissionSettlementBatchServiceInterface`
- `CommissionSettlementBatchExportServiceInterface`

## Neighbor component integration

Pass references instead of entities:

- `order_reference`
- `payment_reference`
- `partner_reference`
- `affiliate_reference`
- `referral_reference`
- `marketplace_seller_reference`
