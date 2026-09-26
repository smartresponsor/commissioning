<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

/**
 * Represents immutable Commissioning value semantics through CommissionMoneyValueObject at typed application boundaries.
 */
final readonly class CommissionMoneyValueObject
{
    public function __construct(
        public string $currencyCode,
        public int $minorAmount,
    ) {
    }
}
