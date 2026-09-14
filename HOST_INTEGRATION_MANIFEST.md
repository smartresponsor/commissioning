# Host Integration Manifest

## Package

`commissioning/commission`

## Namespace

`App\Commissioning`

## Bundle

`App\Commissioning\CommissioningBundle`

## Primary service contracts

- `CommissionEconomicEventCalculationServiceInterface`
- `CommissionEconomicEventRecordServiceInterface`
- `CommissionSettlementReadinessServiceInterface`
- `CommissionSettlementBatchServiceInterface`
- `CommissionSettlementBatchExportServiceInterface`

## Required Doctrine mapping

`App\Commissioning\Entity` mapped to `src/Entity`.

## Host boundary

Host apps pass scalar references and DTOs. Commissioning owns its own entities only.
