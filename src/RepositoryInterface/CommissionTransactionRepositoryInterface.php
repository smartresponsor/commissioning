<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

/**
 * Defines the Commissioning persistence contract exposed by CommissionTransactionRepositoryInterface to application services and resolvers.
 */
interface CommissionTransactionRepositoryInterface
{
    /**
     * @template T
     *
     * @param callable():T $callback
     *
     * @return T
     */
    public function transactional(callable $callback): mixed;
}
