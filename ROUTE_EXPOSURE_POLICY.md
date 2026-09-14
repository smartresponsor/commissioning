# Route Exposure Policy

## Public

`/api/commissioning/...`

Public routes must:

- accept JSON request DTOs
- return response DTOs
- use Serializer/Validator mapping where request body is present
- avoid hardcoded demo payloads
- avoid direct host entity imports

## Demo

`/commissioning/...`

Demo routes must:

- be blocked outside `dev`/`test`
- never be documented as host integration surface
- be candidates for later retirement
