<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionApiCalculationResponseDTO
{
    public function __construct(
        public string $eventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public int $commissionMinorAmount,
        public string $status,
    ) {
    }
}
