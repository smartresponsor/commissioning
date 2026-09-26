<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionRuleOperatorEnum across typed application boundaries.
 */
enum CommissionRuleOperatorEnum: string
{
    case Always = 'always';
    case Equals = 'equals';
    case NotEquals = 'not_equals';
    case GreaterThanOrEqual = 'greater_than_or_equal';
    case LessThanOrEqual = 'less_than_or_equal';
    case In = 'in';
}
