<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionCalculationRequestDTO
{
    public function __construct(
        public string $planCode,
        public string $economicEventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public ?string $attributionReference = null,
    ) {
    }
}
