<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionCalculationStatusEnum across typed application boundaries.
 */
enum CommissionCalculationStatusEnum: string
{
    case Draft = 'draft';
    case Calculated = 'calculated';
    case Reversed = 'reversed';
    case Voided = 'voided';
}
