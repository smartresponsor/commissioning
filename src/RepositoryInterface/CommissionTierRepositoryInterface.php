<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionTierEntity;

interface CommissionTierRepositoryInterface
{
    public function save(CommissionTierEntity $tier): void;

    /**
     * @return list<CommissionTierEntity>
     */
    public function findByRate(CommissionRateEntity $rate): array;
}
