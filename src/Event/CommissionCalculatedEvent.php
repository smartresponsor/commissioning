<?php

declare(strict_types=1);

namespace App\Commissioning\Event;

use App\Commissioning\DTO\CommissionCalculationResultDTO;

final readonly class CommissionCalculatedEvent
{
    public function __construct(public CommissionCalculationResultDTO $result)
    {
    }
}
