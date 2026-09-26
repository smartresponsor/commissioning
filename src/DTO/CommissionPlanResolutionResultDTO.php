<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionPlanResolutionResultDTO application boundary and its callers.
 */
final readonly class CommissionPlanResolutionResultDTO
{
    public function __construct(
        public string $planCode,
        public string $planName,
    ) {
    }
}
