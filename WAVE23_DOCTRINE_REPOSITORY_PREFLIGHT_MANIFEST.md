# Wave 23 Doctrine / Repository Preflight Manifest

Wave 23 performs conservative Doctrine/repository compile preflight hardening.

## Fix targets

- Doctrine mapping compatibility around UUID fields
- repository method return type clarity
- schema readiness docs
- Doctrine config preflight notes
- safe handling of UUID fields through Symfony UID bridge expectations

## Non-claim

This wave does not claim that Doctrine schema validation was executed. It prepares likely compatibility points before actual runtime output.
