<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

final readonly class CommissionBasisValueObject
{
    public function __construct(
        public string $economicEventReference,
        public CommissionMoneyValueObject $money,
        public ?string $attributionReference = null,
    ) {
    }
}
