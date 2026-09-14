<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateResultDTO;

interface CommissionSettlementBatchServiceInterface
{
    public function createBatch(CommissionSettlementBatchCreateRequestDTO $request): CommissionSettlementBatchCreateResultDTO;
}
