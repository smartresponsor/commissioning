<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRecordCalculationResultDTO
{
    public function __construct(
        public string $economicEventReference,
        public string $currencyCode,
        public int $commissionMinorAmount,
        public string $ledgerStatus,
        public bool $duplicate = false,
    ) {
    }
}
