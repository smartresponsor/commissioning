<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionTransactionServiceInterface to typed application collaborators.
 */
interface CommissionTransactionServiceInterface
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
