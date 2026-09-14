# Wave 21 Static Analysis Preflight Manifest

Wave 21 performs conservative static-analysis preflight hardening before actual PHPStan/PHPUnit logs are available.

## Fix targets

- runtime audit no longer depends on checking private services through `ContainerInterface::has`
- public service-id checks are represented as explicit expected service list
- DTO/report arrays have clearer PHPDoc
- PHPStan guidance updated for next real analysis pass
- no runtime mutation, no schema mutation, no destructive operations
