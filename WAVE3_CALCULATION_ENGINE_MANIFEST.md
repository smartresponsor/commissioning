# Wave 3 Calculation Engine Manifest

Wave 3 adds the first canonical Commissioning calculation engine layer.

## Added layers

- `src/Calculator`
- `src/CalculatorInterface`
- `src/Resolver`
- `src/ResolverInterface`

These are type-identifiable Symfony-oriented layers, not Port-and-Adapter layers.

## Added responsibilities

- percentage commission calculation
- fixed commission calculation
- hybrid commission calculation
- tiered commission calculation
- calculation engine selection by rate type
- rule context value object
- beneficiary resolution skeleton
- calculation result with detailed lines

## Canon

The component remains entity-first. Calculation services do not own payment capture, payout execution, tax, product pricing, discounting, couponing, or subscription lifecycle.
