<?php

declare(strict_types=1);

namespace App\Commissioning\Resolver;

use App\Commissioning\DTO\CommissionBeneficiaryResolutionResultDTO;
use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;
use App\Commissioning\ResolverInterface\CommissionBeneficiaryResolverInterface;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;

final class CommissionBeneficiaryResolver implements CommissionBeneficiaryResolverInterface
{
    public function resolve(CommissionRuleContextValueObject $context): CommissionBeneficiaryResolutionResultDTO
    {
        $type = (string) ($context->get('beneficiary_type') ?? CommissionBeneficiaryTypeEnum::Partner->value);
        $reference = (string) ($context->get('beneficiary_reference') ?? 'unresolved');

        return new CommissionBeneficiaryResolutionResultDTO(
            beneficiaryType: $type,
            beneficiaryReference: $reference,
            displayName: null,
        );
    }
}
