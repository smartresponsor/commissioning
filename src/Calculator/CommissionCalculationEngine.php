<?php

declare(strict_types=1);

namespace App\Commissioning\Calculator;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\CalculatorInterface\CommissionCalculatorInterface;
use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

/**
 * Calculates canonical Commissioning amounts through CommissionCalculationEngine using typed basis and rate inputs.
 */
final class CommissionCalculationEngine implements CommissionCalculationEngineInterface
{
    /**
     * @param iterable<CommissionCalculatorInterface> $calculators
     */
    public function __construct(private readonly iterable $calculators)
    {
    }

    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO {
        foreach ($this->calculators as $calculator) {
            if ($calculator->supports($rate->rateType)) {
                return $calculator->calculate($basis, $rate);
            }
        }

        throw new \InvalidArgumentException(sprintf('Unsupported commission rate type "%s".', $rate->rateType));
    }
}
