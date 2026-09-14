<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRateResolutionRequestDTO
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function __construct(
        public string $planCode,
        public string $currencyCode,
        public int $basisMinorAmount,
        public array $context = [],
    ) {
    }
}
