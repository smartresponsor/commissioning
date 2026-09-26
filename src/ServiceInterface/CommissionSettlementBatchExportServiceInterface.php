<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementBatchExportDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionSettlementBatchExportServiceInterface to typed application collaborators.
 */
interface CommissionSettlementBatchExportServiceInterface
{
    /**
     * Exports canonical Commissioning settlement data through the typed application handoff contract.
     */
    public function exportBatch(string $batchReference): CommissionSettlementBatchExportDTO;
}
