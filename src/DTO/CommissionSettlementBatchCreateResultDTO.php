<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionSettlementBatchCreateResultDTO
{
    public function __construct(
        public string $batchReference,
        public string $status,
        public int $entryCount,
        public int $totalMinorAmount,
        public int $duplicateEntryCount = 0,
    ) {
    }
}
