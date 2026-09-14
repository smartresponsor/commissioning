<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;

interface CommissionEconomicEventCalculationServiceInterface
{
    public function calculateForEconomicEvent(CommissionEconomicEventDTO $event): CommissionCalculationResultDTO;
}
