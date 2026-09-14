# Config Consolidation Notes

## Why this exists

Earlier waves appended service aliases as the component grew. Symfony YAML service ids should be unique; duplicate ids can cause hard-to-read container behavior or override earlier definitions.

## Wave 20 action

`config/services.yaml` is rewritten as one canonical consolidated file.

## Expected benefit

- fewer container compile surprises
- easier review
- easier host import
- clearer runtime audit behavior
