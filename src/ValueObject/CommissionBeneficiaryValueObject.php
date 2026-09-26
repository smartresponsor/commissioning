<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;

/**
 * Represents immutable Commissioning value semantics through CommissionBeneficiaryValueObject at typed application boundaries.
 */
final readonly class CommissionBeneficiaryValueObject
{
    public function __construct(
        public CommissionBeneficiaryTypeEnum $type,
        public string $reference,
        public ?string $displayName = null,
    ) {
    }
}
