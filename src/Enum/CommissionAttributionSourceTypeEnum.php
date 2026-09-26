<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionAttributionSourceTypeEnum across typed application boundaries.
 */
enum CommissionAttributionSourceTypeEnum: string
{
    case Affiliate = 'affiliate';
    case Referral = 'referral';
    case Reseller = 'reseller';
    case MarketplaceSeller = 'marketplace_seller';
    case Partner = 'partner';
}
