<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionRuntimeCheckResultDTO application boundary and its callers.
 */
final readonly class CommissionRuntimeCheckResultDTO
{
    /**
     * @param list<string> $messages
     */
    public function __construct(
        public string $nameEntity,
        public string $status,
        public array $messages = [],
    ) {
    }
}
