<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

final readonly class CommissionRuleContextValueObject
{
    /**
     * @param array<string, scalar|null> $values
     */
    public function __construct(public array $values)
    {
    }

    public function get(string $key): bool|float|int|string|null
    {
        return $this->values[$key] ?? null;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
