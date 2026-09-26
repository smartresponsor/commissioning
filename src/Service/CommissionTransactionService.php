<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionTransactionServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionTransactionService across typed collaborators and boundaries.
 */
final class CommissionTransactionService implements CommissionTransactionServiceInterface
{
    public function __construct(private readonly CommissionTransactionRepositoryInterface $transactionRepository)
    {
    }

    /**
     * Executes the supplied Commissioning callback within the configured persistence transaction boundary.
     */
    public function transactional(callable $callback): mixed
    {
        return $this->transactionRepository->transactional($callback);
    }
}
