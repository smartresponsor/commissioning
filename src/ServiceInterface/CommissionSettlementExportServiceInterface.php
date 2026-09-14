<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementExportDTO;

interface CommissionSettlementExportServiceInterface
{
    public function exportSettlementReady(string $batchReference): CommissionSettlementExportDTO;
}
