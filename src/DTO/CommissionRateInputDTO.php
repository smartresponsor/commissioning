<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRateInputDTO
{
    /**
     * @param list<array{minimumMinorAmount:int, maximumMinorAmount?:int|null, percentageRate:string}> $tiers
     */
    public function __construct(
        public string $rateType,
        public ?string $percentageRate = null,
        public ?int $fixedMinorAmount = null,
        public ?string $currencyCode = null,
        public array $tiers = [],
    ) {
    }
}
