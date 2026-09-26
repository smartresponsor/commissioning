<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionCalculationRequestDTO application boundary and its callers.
 */
final readonly class CommissionCalculationRequestDTO
{
    public function __construct(
        public string $planCode,
        public string $economicEventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public ?string $attributionReference = null,
    ) {
    }
}
