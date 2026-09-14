<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

enum CommissionSettlementBatchStatusEnum: string
{
    case Draft = 'draft';
    case Exported = 'exported';
    case Accepted = 'accepted';
    case Cancelled = 'cancelled';
}
