<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionEconomicEventDTO
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function __construct(
        public string $eventReference,
        public string $currencyCode,
        public int $basisMinorAmount,
        public ?string $planCode = null,
        public ?string $attributionSourceType = null,
        public ?string $attributionSourceReference = null,
        public array $context = [],
    ) {
    }
}
