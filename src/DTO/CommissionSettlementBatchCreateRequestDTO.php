<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionSettlementBatchCreateRequestDTO
{
    public function __construct(
        public string $batchReference,
        public ?string $beneficiaryReference = null,
    ) {
    }
}
