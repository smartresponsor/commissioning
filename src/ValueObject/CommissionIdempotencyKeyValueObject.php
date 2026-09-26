<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

/**
 * Represents immutable Commissioning value semantics through CommissionIdempotencyKeyValueObject at typed application boundaries.
 */
final readonly class CommissionIdempotencyKeyValueObject
{
    public function __construct(public string $value)
    {
        if ('' === trim($value)) {
            throw new \InvalidArgumentException('Commission idempotency key cannot be empty.');
        }
    }
}
