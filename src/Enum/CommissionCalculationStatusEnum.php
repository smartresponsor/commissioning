<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

enum CommissionCalculationStatusEnum: string
{
    case Draft = 'draft';
    case Calculated = 'calculated';
    case Reversed = 'reversed';
    case Voided = 'voided';
}
