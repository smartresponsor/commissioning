<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionSettlementExportDTO;
use App\Commissioning\ServiceInterface\CommissionSettlementExportServiceInterface;

final class CommissionSettlementExportService implements CommissionSettlementExportServiceInterface
{
    public function exportSettlementReady(string $batchReference): CommissionSettlementExportDTO
    {
        return new CommissionSettlementExportDTO($batchReference, []);
    }
}
