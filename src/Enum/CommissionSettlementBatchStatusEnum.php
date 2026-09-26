<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionSettlementBatchStatusEnum across typed application boundaries.
 */
enum CommissionSettlementBatchStatusEnum: string
{
    case Draft = 'draft';
    case Exported = 'exported';
    case Accepted = 'accepted';
    case Cancelled = 'cancelled';
}
