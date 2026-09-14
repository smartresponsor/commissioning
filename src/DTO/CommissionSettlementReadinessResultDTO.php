<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionSettlementReadinessResultDTO
{
    public function __construct(
        public int $markedCount,
    ) {
    }
}
