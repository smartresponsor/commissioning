#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${BASE_URL:-http://localhost:8000}"

request() {
  local name="$1"
  local method="$2"
  local path="$3"
  local payload="${4:-}"

  echo
  echo "==> ${name}"
  if [[ -n "$payload" ]]; then
    curl -sS -X "$method" "${BASE_URL}${path}" \
      -H "Content-Type: application/json" \
      --data @"$payload"
  else
    curl -sS -X "$method" "${BASE_URL}${path}"
  fi
  echo
}

request "preview_percentage_calculation" "POST" "/api/commissioning/calculation/preview" "docs/smoke/payloads/calculation-preview-percentage.json"
request "record_fixed_calculation" "POST" "/api/commissioning/calculation" "docs/smoke/payloads/calculation-record-fixed.json"
request "record_duplicate_calculation" "POST" "/api/commissioning/calculation" "docs/smoke/payloads/calculation-record-duplicate.json"
request "mark_settlement_ready" "POST" "/api/commissioning/ledger/settlement/ready" "docs/smoke/payloads/settlement-ready.json"
request "create_settlement_batch" "POST" "/api/commissioning/settlement/batch" "docs/smoke/payloads/settlement-batch-create.json"
request "export_settlement_batch" "GET" "/api/commissioning/settlement/batch/smoke-settlement-batch-1001/export"

echo
echo "Commissioning API smoke sequence completed."
