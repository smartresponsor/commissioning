<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionBeneficiaryResolutionResultDTO;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionBeneficiaryResolverInterface to typed application collaborators.
 */
interface CommissionBeneficiaryResolverInterface
{
    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
    public function resolve(CommissionRuleContextValueObject $context): CommissionBeneficiaryResolutionResultDTO;
}
