<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionAttributionResolutionResultDTO application boundary and its callers.
 */
final readonly class CommissionAttributionResolutionResultDTO
{
    public function __construct(
        public string $sourceType,
        public string $sourceReference,
        public string $economicEventReference,
    ) {
    }
}
