<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionPlanResolutionResultDTO
{
    public function __construct(
        public string $planCode,
        public string $planName,
    ) {
    }
}
