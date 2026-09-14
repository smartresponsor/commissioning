<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\ServiceInterface\CommissionTransactionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CommissionTransactionService implements CommissionTransactionServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function transactional(callable $callback): mixed
    {
        return $this->entityManager->wrapInTransaction($callback);
    }
}
