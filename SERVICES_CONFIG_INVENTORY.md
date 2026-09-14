# Services Config Inventory

## Discovery blocks

- `App\Commissioning\` for source services
- `App\Commissioning\Command\`
- `App\Commissioning\Controller\`
- `App\Commissioning\RepositoryInterface\`
- `App\Commissioning\ServiceInterface\`
- `App\Commissioning\Calculator\`

## Tagged iterator

`CommissionCalculationEngine` receives all services tagged `commissioning.calculator`.

## Public aliases

Only runtime-audited primary API/application contracts are public:

- `CommissionEconomicEventCalculationServiceInterface`
- `CommissionEconomicEventRecordServiceInterface`
- `CommissionSettlementBatchServiceInterface`
- `CommissionSettlementBatchExportServiceInterface`

## Demo route guard

`CommissionDemoRouteGuardService` receives `%kernel.environment%`.
