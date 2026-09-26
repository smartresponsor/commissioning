<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionCalculationServiceInterface to typed application collaborators.
 */
interface CommissionCalculationServiceInterface
{
    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(CommissionCalculationRequestDTO $request): CommissionCalculationResultDTO;
}
