<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionBeneficiaryInputDTO
{
    public function __construct(
        public string $beneficiaryType,
        public string $beneficiaryReference,
        public ?string $displayName = null,
    ) {
    }
}
