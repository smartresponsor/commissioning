<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Carries typed Commissioning data for the CommissionApiSettlementReadyRequestDTO application boundary and its callers.
 */
final readonly class CommissionApiSettlementReadyRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 120)]
        public string $beneficiaryReference,
    ) {
    }
}
