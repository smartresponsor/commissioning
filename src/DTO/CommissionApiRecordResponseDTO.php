<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionApiRecordResponseDTO application boundary and its callers.
 */
final readonly class CommissionApiRecordResponseDTO
{
    public function __construct(
        public string $eventReference,
        public string $currencyCode,
        public int $commissionMinorAmount,
        public string $ledgerStatus,
        public bool $duplicate = false,
    ) {
    }
}
