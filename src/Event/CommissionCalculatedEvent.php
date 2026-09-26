<?php

declare(strict_types=1);

namespace App\Commissioning\Event;

use App\Commissioning\DTO\CommissionCalculationResultDTO;

/**
 * Carries the Commissioning event payload represented by CommissionCalculatedEvent across synchronous application listeners.
 */
final readonly class CommissionCalculatedEvent
{
    public function __construct(public CommissionCalculationResultDTO $result)
    {
    }
}
