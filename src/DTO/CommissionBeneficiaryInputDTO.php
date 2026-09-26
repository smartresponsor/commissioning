<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

/**
 * Carries typed Commissioning data for the CommissionBeneficiaryInputDTO application boundary and its callers.
 */
final readonly class CommissionBeneficiaryInputDTO
{
    public function __construct(
        public string $beneficiaryType,
        public string $beneficiaryReference,
        public ?string $displayName = null,
    ) {
    }
}
