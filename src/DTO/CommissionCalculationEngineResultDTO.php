<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionCalculationEngineResultDTO
{
    /**
     * @param array<int, CommissionCalculationLineResultDTO> $lines
     */
    public function __construct(
        public string $currencyCode,
        public int $basisMinorAmount,
        public int $commissionMinorAmount,
        public array $lines,
    ) {
    }
}
