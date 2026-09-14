<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

enum CommissionCalculationLineTypeEnum: string
{
    case Base = 'base';
    case PercentageCommission = 'percentage_commission';
    case FixedCommission = 'fixed_commission';
    case TierAdjustment = 'tier_adjustment';
    case Reversal = 'reversal';
}
