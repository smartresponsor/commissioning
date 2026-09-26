<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionSettlementExportDTO application boundary and its callers.
 */
final readonly class CommissionSettlementExportDTO
{
    /**
     * @param array<int, array<string, scalar|null>> $entries
     */
    public function __construct(
        public string $batchReference,
        public array $entries,
    ) {
    }
}
