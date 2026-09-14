<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionBeneficiaryResolutionResultDTO;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;

interface CommissionBeneficiaryResolverInterface
{
    public function resolve(CommissionRuleContextValueObject $context): CommissionBeneficiaryResolutionResultDTO;
}
