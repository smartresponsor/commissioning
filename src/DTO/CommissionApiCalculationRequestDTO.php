<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CommissionApiCalculationRequestDTO
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 120)]
        public string $eventReference,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 3)]
        public string $currencyCode,

        #[Assert\PositiveOrZero]
        public int $basisMinorAmount,

        #[Assert\Length(max: 120)]
        public ?string $planCode = null,

        #[Assert\Length(max: 120)]
        public ?string $attributionSourceType = null,

        #[Assert\Length(max: 120)]
        public ?string $attributionSourceReference = null,

        public array $context = [],
    ) {
    }
}
