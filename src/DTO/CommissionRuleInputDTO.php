<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRuleInputDTO
{
    public function __construct(
        public string $ruleKey,
        public string $operator,
        public ?string $expectedValue,
        public int $priority = 100,
    ) {
    }
}
