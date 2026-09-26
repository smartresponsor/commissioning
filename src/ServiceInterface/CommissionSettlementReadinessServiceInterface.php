<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementReadinessResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionSettlementReadinessServiceInterface to typed application collaborators.
 */
interface CommissionSettlementReadinessServiceInterface
{
    /**
     * Applies the requested Commissioning lifecycle transition and returns the resulting application state.
     */
    public function markReadyForBeneficiary(string $beneficiaryReference): CommissionSettlementReadinessResultDTO;
}
