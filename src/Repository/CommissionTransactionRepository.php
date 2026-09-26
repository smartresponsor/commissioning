<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Persists and queries Commissioning records through the CommissionTransactionRepository Doctrine repository boundary.
 */
final class CommissionTransactionRepository implements CommissionTransactionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Executes the supplied Commissioning callback within the configured persistence transaction boundary.
     */
    public function transactional(callable $callback): mixed
    {
        return $this->entityManager->wrapInTransaction($callback);
    }
}
