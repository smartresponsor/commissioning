<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

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
