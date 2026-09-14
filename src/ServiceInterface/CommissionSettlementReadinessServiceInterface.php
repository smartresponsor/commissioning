<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementReadinessResultDTO;

interface CommissionSettlementReadinessServiceInterface
{
    public function markReadyForBeneficiary(string $beneficiaryReference): CommissionSettlementReadinessResultDTO;
}
