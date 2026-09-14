# Commissioning API Contract

This document is a machine-readable seed for future OpenAPI generation.

## POST `/api/commissioning/calculation/preview`

Calculates commission without persistence.

Input shape: `CommissionApiCalculationRequestDTO`  
Output shape: `CommissionApiCalculationResponseDTO`

## POST `/api/commissioning/calculation`

Calculates and persists commission.

Input shape: `CommissionApiCalculationRequestDTO`  
Output shape: `CommissionApiRecordResponseDTO`

## POST `/api/commissioning/ledger/settlement/ready`

Marks pending ledger entries settlement-ready for a beneficiary.

Input shape: `CommissionApiSettlementReadyRequestDTO`  
Output shape: `CommissionApiSettlementReadyResponseDTO`

## POST `/api/commissioning/settlement/batch`

Creates a settlement batch.

Input shape: `CommissionApiSettlementBatchCreateRequestDTO`  
Output shape: `CommissionApiSettlementBatchCreateResponseDTO`

## GET `/api/commissioning/settlement/batch/export/{batchReference}`

Exports a settlement batch.

Output shape: `CommissionApiSettlementBatchExportResponseDTO`
