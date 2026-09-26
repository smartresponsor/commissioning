<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionSettlementReadinessResultDTO application boundary and its callers.
 */
final readonly class CommissionSettlementReadinessResultDTO
{
    public function __construct(
        public int $markedCount,
    ) {
    }
}
