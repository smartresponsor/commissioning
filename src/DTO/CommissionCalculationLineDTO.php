<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionCalculationLineDTO application boundary and its callers.
 */
final readonly class CommissionCalculationLineDTO
{
    public function __construct(
        public string $lineType,
        public string $currencyCode,
        public int $minorAmount,
        public ?string $explanation = null,
    ) {
    }
}
