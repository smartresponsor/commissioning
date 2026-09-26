<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionBeneficiaryTypeEnum across typed application boundaries.
 */
enum CommissionBeneficiaryTypeEnum: string
{
    case Partner = 'partner';
    case Affiliate = 'affiliate';
    case Reseller = 'reseller';
    case MarketplaceSeller = 'marketplace_seller';
    case InternalAccount = 'internal_account';
}
