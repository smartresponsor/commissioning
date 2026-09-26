<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionCalculationLineTypeEnum across typed application boundaries.
 */
enum CommissionCalculationLineTypeEnum: string
{
    case Base = 'base';
    case PercentageCommission = 'percentage_commission';
    case FixedCommission = 'fixed_commission';
    case TierAdjustment = 'tier_adjustment';
    case Reversal = 'reversal';
}
