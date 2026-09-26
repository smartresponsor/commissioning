<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Carries typed Commissioning data for the CommissionApiSettlementBatchCreateRequestDTO application boundary and its callers.
 */
final readonly class CommissionApiSettlementBatchCreateRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 120)]
        public string $batchReference,

        #[Assert\Length(max: 120)]
        public ?string $beneficiaryReference = null,
    ) {
    }
}
