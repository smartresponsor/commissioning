param(
    [string]$BaseUrl = "http://localhost:8000"
)

$ErrorActionPreference = "Stop"

function Invoke-SmokeRequest {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Path,
        [string]$PayloadPath = ""
    )

    Write-Host ""
    Write-Host "==> $Name"

    $uri = "$BaseUrl$Path"

    if ($PayloadPath -ne "") {
        $body = Get-Content -LiteralPath $PayloadPath -Raw
        Invoke-RestMethod -Method $Method -Uri $uri -ContentType "application/json" -Body $body | ConvertTo-Json -Depth 10
    } else {
        Invoke-RestMethod -Method $Method -Uri $uri | ConvertTo-Json -Depth 10
    }
}

Invoke-SmokeRequest "preview_percentage_calculation" "POST" "/api/commissioning/calculation/preview" "docs/smoke/payloads/calculation-preview-percentage.json"
Invoke-SmokeRequest "record_fixed_calculation" "POST" "/api/commissioning/calculation" "docs/smoke/payloads/calculation-record-fixed.json"
Invoke-SmokeRequest "record_duplicate_calculation" "POST" "/api/commissioning/calculation" "docs/smoke/payloads/calculation-record-duplicate.json"
Invoke-SmokeRequest "mark_settlement_ready" "POST" "/api/commissioning/ledger/settlement/ready" "docs/smoke/payloads/settlement-ready.json"
Invoke-SmokeRequest "create_settlement_batch" "POST" "/api/commissioning/settlement/batch" "docs/smoke/payloads/settlement-batch-create.json"
Invoke-SmokeRequest "export_settlement_batch" "GET" "/api/commissioning/settlement/batch/smoke-settlement-batch-1001/export"

Write-Host ""
Write-Host "Commissioning API smoke sequence completed."
