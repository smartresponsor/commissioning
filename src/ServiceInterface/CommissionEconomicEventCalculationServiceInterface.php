<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionEconomicEventCalculationServiceInterface to typed application collaborators.
 */
interface CommissionEconomicEventCalculationServiceInterface
{
    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculateForEconomicEvent(CommissionEconomicEventDTO $event): CommissionCalculationResultDTO;
}
