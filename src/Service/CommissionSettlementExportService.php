<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionSettlementExportDTO;
use App\Commissioning\ServiceInterface\CommissionSettlementExportServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionSettlementExportService across typed collaborators and boundaries.
 */
final class CommissionSettlementExportService implements CommissionSettlementExportServiceInterface
{
    /**
     * Exports canonical Commissioning settlement data through the typed application handoff contract.
     */
    public function exportSettlementReady(string $batchReference): CommissionSettlementExportDTO
    {
        return new CommissionSettlementExportDTO($batchReference, []);
    }
}
