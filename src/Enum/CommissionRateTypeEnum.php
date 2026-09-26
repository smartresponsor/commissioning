<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionRateTypeEnum across typed application boundaries.
 */
enum CommissionRateTypeEnum: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';
    case Hybrid = 'hybrid';
    case Tiered = 'tiered';
}
