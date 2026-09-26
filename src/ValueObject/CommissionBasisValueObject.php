<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

/**
 * Represents immutable Commissioning value semantics through CommissionBasisValueObject at typed application boundaries.
 */
final readonly class CommissionBasisValueObject
{
    public function __construct(
        public string $economicEventReference,
        public CommissionMoneyValueObject $money,
        public ?string $attributionReference = null,
    ) {
    }
}
