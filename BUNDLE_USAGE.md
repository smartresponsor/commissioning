# Bundle Usage Notes

Commissioning is built as a standalone Symfony app and bundle-usable component.

## Bundle entry

`App\Commissioning\CommissioningBundle`

## Host app expectations

A host application should:

1. Register or discover the bundle entry.
2. Import service configuration or rely on autoconfiguration.
3. Configure Doctrine mapping for `App\Commissioning\Entity`.
4. Keep `commission_` table prefix intact.
5. Avoid importing host entities into Commissioning.
6. Communicate with Commissioning through DTOs, scalar references, and services.

## Boundary

Commissioning exports settlement instructions. Paying/Payouting owns actual payout execution.
