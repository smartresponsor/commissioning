# Wave 13 Host / Bundle Integration Manifest

Wave 13 adds host-app and bundle integration proof package documentation.

## Added responsibilities

- composer path repository example
- Symfony bundle registration notes
- Doctrine mapping examples for local path and vendor install
- services import notes
- host smoke checklist
- host boundary contract
- integration DTO/reference guidance

## Boundary rule

Host applications pass scalar references and DTO payloads. Commissioning must not import host entities from Ordering, Paying, Taxating, Pricing, Currencing, Subscriptioning, or other sibling components.
