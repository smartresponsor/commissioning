<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionSettlementExportDTO
{
    /**
     * @param array<int, array<string, scalar|null>> $entries
     */
    public function __construct(
        public string $batchReference,
        public array $entries,
    ) {
    }
}
