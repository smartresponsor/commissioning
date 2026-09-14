<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CommissionApiSettlementReadyRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 120)]
        public string $beneficiaryReference,
    ) {
    }
}
