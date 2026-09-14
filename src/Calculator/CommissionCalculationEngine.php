<?php

declare(strict_types=1);

namespace App\Commissioning\Calculator;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\CalculatorInterface\CommissionCalculatorInterface;
use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

final class CommissionCalculationEngine implements CommissionCalculationEngineInterface
{
    /**
     * @param iterable<CommissionCalculatorInterface> $calculators
     */
    public function __construct(private readonly iterable $calculators)
    {
    }

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
