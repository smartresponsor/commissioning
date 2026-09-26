<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionSettlementBatchServiceInterface to typed application collaborators.
 */
interface CommissionSettlementBatchServiceInterface
{
    /**
     * Performs the createBatch operation defined by this typed Commissioning application contract.
     */
    public function createBatch(CommissionSettlementBatchCreateRequestDTO $request): CommissionSettlementBatchCreateResultDTO;
}
