<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionTypeEntity;

interface CommissionTypeRepositoryInterface
{
    public function save(CommissionTypeEntity $entity): void;

    public function findOneByCode(string $code): ?CommissionTypeEntity;
}
