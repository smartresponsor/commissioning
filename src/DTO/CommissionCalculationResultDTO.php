<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionCalculationResultDTO application boundary and its callers.
 */
final readonly class CommissionCalculationResultDTO
{
    public function __construct(
        public string $economicEventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public int $commissionMinorAmount,
        public string $status,
    ) {
    }
}
