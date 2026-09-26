<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionIdempotencyResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionIdempotencyServiceInterface to typed application collaborators.
 */
interface CommissionIdempotencyServiceInterface
{
    /**
     * Checks the supplied Commissioning state against this explicit application readiness contract.
     */
    public function checkEconomicEvent(string $economicEventReference): CommissionIdempotencyResultDTO;
}
