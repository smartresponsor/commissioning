# Wave 20 Services Config Consolidation Manifest

Wave 20 consolidates Symfony service configuration accumulated across earlier waves.

## Fix target

Earlier iterative waves appended aliases into `config/services.yaml`. This can create repeated service ids and make Symfony container compilation fragile.

## What this wave does

- replaces `config/services.yaml` with a canonical consolidated version
- keeps all known service/interface aliases
- keeps calculator tagged iterator
- keeps command/controller/service discovery
- keeps runtime-audit-visible public API aliases
- keeps demo route guard environment argument

## Non-claim

This wave still does not claim that `cache:clear` has been executed. It reduces a likely pre-runtime container compilation risk.
