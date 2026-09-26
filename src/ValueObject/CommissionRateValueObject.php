<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

use App\Commissioning\Enum\CommissionRateTypeEnum;

/**
 * Represents immutable Commissioning value semantics through CommissionRateValueObject at typed application boundaries.
 */
final readonly class CommissionRateValueObject
{
    public function __construct(
        public CommissionRateTypeEnum $type,
        public ?string $percentageRate,
        public ?CommissionMoneyValueObject $fixedAmount,
    ) {
    }
}
