<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionDirectionEntity;

interface CommissionDirectionRepositoryInterface
{
    public function save(CommissionDirectionEntity $entity): void;

    public function findOneByCode(string $code): ?CommissionDirectionEntity;
}
