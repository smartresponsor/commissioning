<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionEconomicEventRecordServiceInterface to typed application collaborators.
 */
interface CommissionEconomicEventRecordServiceInterface
{
    /**
     * Performs the recordEconomicEvent operation defined by this typed Commissioning application contract.
     */
    public function recordEconomicEvent(CommissionEconomicEventDTO $event): CommissionRecordCalculationResultDTO;
}
