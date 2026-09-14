# Curl Commands

Set base URL:

```bash
BASE_URL="${BASE_URL:-http://localhost:8000}"
```

## Preview calculation

```bash
curl -sS -X POST "$BASE_URL/api/commissioning/calculation/preview" \
  -H "Content-Type: application/json" \
  --data @docs/smoke/payloads/calculation-preview-percentage.json
```

## Record calculation

```bash
curl -sS -X POST "$BASE_URL/api/commissioning/calculation" \
  -H "Content-Type: application/json" \
  --data @docs/smoke/payloads/calculation-record-fixed.json
```

## Record duplicate calculation

```bash
curl -sS -X POST "$BASE_URL/api/commissioning/calculation" \
  -H "Content-Type: application/json" \
  --data @docs/smoke/payloads/calculation-record-duplicate.json
```

Expected semantic result: `"duplicate": true`.

## Mark settlement-ready

```bash
curl -sS -X POST "$BASE_URL/api/commissioning/ledger/settlement/ready" \
  -H "Content-Type: application/json" \
  --data @docs/smoke/payloads/settlement-ready.json
```

## Create settlement batch

```bash
curl -sS -X POST "$BASE_URL/api/commissioning/settlement/batch" \
  -H "Content-Type: application/json" \
  --data @docs/smoke/payloads/settlement-batch-create.json
```

## Export settlement batch

```bash
curl -sS "$BASE_URL/api/commissioning/settlement/batch/smoke-settlement-batch-1001/export"
```
