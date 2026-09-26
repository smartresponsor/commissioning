<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionApiErrorResponseDTO application boundary and its callers.
 */
final readonly class CommissionApiErrorResponseDTO
{
    /**
     * @param array<int, string> $violations
     */
    public function __construct(
        public string $message,
        public array $violations = [],
    ) {
    }
}
