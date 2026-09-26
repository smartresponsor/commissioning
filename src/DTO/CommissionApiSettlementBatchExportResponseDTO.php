<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionApiSettlementBatchExportResponseDTO application boundary and its callers.
 */
final readonly class CommissionApiSettlementBatchExportResponseDTO
{
    /**
     * @param array<int, CommissionSettlementEntryExportDTO> $entries
     */
    public function __construct(
        public string $batchReference,
        public string $status,
        public int $entryCount,
        public int $totalMinorAmount,
        public array $entries,
    ) {
    }
}
