<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionSettlementEntryExportDTO application boundary and its callers.
 */
final readonly class CommissionSettlementEntryExportDTO
{
    public function __construct(
        public string $beneficiaryReference,
        public string $currencyCode,
        public int $minorAmount,
        public string $sourceStatus,
    ) {
    }
}
