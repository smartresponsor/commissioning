<?php

declare(strict_types=1);

namespace App\Commissioning\CalculatorInterface;

use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionCalculatorInterface to typed application collaborators.
 */
interface CommissionCalculatorInterface
{
    /**
     * Reports whether this calculator supports the supplied canonical Commissioning rate definition.
     */
    public function supports(string $rateType): bool;

    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO;
}
