<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionIdempotencyResultDTO;

interface CommissionIdempotencyServiceInterface
{
    public function checkEconomicEvent(string $economicEventReference): CommissionIdempotencyResultDTO;
}
