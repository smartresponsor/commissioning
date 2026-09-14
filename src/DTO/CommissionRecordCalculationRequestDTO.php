<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRecordCalculationRequestDTO
{
    /**
     * @param array<int, CommissionCalculationLineResultDTO> $lines
     */
    public function __construct(
        public string $planCode,
        public string $planName,
        public string $economicEventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public int $commissionMinorAmount,
        public string $beneficiaryReference,
        public array $lines,
    ) {
    }
}
