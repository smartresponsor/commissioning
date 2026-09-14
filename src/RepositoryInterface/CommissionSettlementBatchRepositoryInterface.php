<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionSettlementBatchEntity;

interface CommissionSettlementBatchRepositoryInterface
{
    public function save(CommissionSettlementBatchEntity $batch): void;

    public function findOneByBatchReference(string $batchReference): ?CommissionSettlementBatchEntity;
}
