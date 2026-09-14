<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

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
