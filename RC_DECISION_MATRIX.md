# RC Decision Matrix

## Current state

| Area | Status | Decision |
|---|---|---|
| Canon structure | Strong | Keep |
| Entity-first model | Strong | Keep |
| Calculation engine | Solid foundation | Harden with tests |
| Repository-backed resolution | Partial/Solid | Needs fixture/runtime proof |
| API request mapping | Partial/Solid | Needs serializer smoke |
| Idempotency | Partial/Solid | Needs transaction proof |
| Settlement export | Solid foundation | Needs payout handoff fixture |
| Host integration docs | Solid | Needs actual host proof |
| Runtime proof | Pending | Next milestone |
| Security/auth | Absent by intent | Add only when host boundary requires |

## RC gate recommendation

Do not call this production-ready yet.

Call it:

**M2 static/runtime-prep RC candidate**

Move to runtime proof next.
