<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementExportDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionSettlementExportServiceInterface to typed application collaborators.
 */
interface CommissionSettlementExportServiceInterface
{
    /**
     * Exports canonical Commissioning settlement data through the typed application handoff contract.
     */
    public function exportSettlementReady(string $batchReference): CommissionSettlementExportDTO;
}
