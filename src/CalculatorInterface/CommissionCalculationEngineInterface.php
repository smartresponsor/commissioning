<?php

declare(strict_types=1);

namespace App\Commissioning\CalculatorInterface;

use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionCalculationEngineInterface to typed application collaborators.
 */
interface CommissionCalculationEngineInterface
{
    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO;
}
