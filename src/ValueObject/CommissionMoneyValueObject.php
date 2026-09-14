<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

final readonly class CommissionMoneyValueObject
{
    public function __construct(
        public string $currencyCode,
        public int $minorAmount,
    ) {
    }
}
