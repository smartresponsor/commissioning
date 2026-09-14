# API Request Examples

## Preview calculation

```json
{
  "eventReference": "order-1001",
  "currencyCode": "USD",
  "basisMinorAmount": 10000,
  "planCode": "default",
  "attributionSourceType": "partner",
  "attributionSourceReference": "partner-123",
  "context": {
    "commission_rate_type": "percentage",
    "commission_percentage_rate": "10"
  }
}
```

## Record calculation

```json
{
  "eventReference": "order-1001",
  "currencyCode": "USD",
  "basisMinorAmount": 10000,
  "planCode": "default",
  "attributionSourceType": "affiliate",
  "attributionSourceReference": "affiliate-42",
  "context": {
    "commission_rate_type": "hybrid",
    "commission_percentage_rate": "7.5",
    "commission_fixed_minor_amount": 250
  }
}
```

## Mark settlement-ready

```json
{
  "beneficiaryReference": "affiliate-42"
}
```

## Create settlement batch

```json
{
  "batchReference": "commission-settlement-2026-05-03",
  "beneficiaryReference": "affiliate-42"
}
```
