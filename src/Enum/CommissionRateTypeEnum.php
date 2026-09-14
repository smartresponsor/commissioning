<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

enum CommissionRateTypeEnum: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';
    case Hybrid = 'hybrid';
    case Tiered = 'tiered';
}
