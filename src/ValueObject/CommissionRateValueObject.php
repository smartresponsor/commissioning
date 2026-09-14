<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

use App\Commissioning\Enum\CommissionRateTypeEnum;

final readonly class CommissionRateValueObject
{
    public function __construct(
        public CommissionRateTypeEnum $type,
        public ?string $percentageRate,
        public ?CommissionMoneyValueObject $fixedAmount,
    ) {
    }
}
