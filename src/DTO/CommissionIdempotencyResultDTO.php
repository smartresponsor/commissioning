<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionIdempotencyResultDTO application boundary and its callers.
 */
final readonly class CommissionIdempotencyResultDTO
{
    public function __construct(
        public bool $duplicate,
        public string $reference,
    ) {
    }
}
