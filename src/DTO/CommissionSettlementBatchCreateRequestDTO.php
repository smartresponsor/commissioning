<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionSettlementBatchCreateRequestDTO application boundary and its callers.
 */
final readonly class CommissionSettlementBatchCreateRequestDTO
{
    public function __construct(
        public string $batchReference,
        public ?string $beneficiaryReference = null,
    ) {
    }
}
