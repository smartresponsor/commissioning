<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

enum CommissionAttributionSourceTypeEnum: string
{
    case Affiliate = 'affiliate';
    case Referral = 'referral';
    case Reseller = 'reseller';
    case MarketplaceSeller = 'marketplace_seller';
    case Partner = 'partner';
}
