<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionPlanResolutionRequestDTO application boundary and its callers.
 */
final readonly class CommissionPlanResolutionRequestDTO
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function __construct(
        public ?string $planCode,
        public array $context = [],
    ) {
    }
}
