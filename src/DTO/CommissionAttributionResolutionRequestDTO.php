<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionAttributionResolutionRequestDTO
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function __construct(
        public string $economicEventReference,
        public ?string $sourceType,
        public ?string $sourceReference,
        public array $context = [],
    ) {
    }
}
