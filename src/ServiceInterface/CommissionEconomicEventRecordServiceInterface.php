<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;

interface CommissionEconomicEventRecordServiceInterface
{
    public function recordEconomicEvent(CommissionEconomicEventDTO $event): CommissionRecordCalculationResultDTO;
}
