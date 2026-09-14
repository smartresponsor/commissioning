<?php

declare(strict_types=1);

namespace App\Commissioning\CalculatorInterface;

use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

interface CommissionCalculatorInterface
{
    public function supports(string $rateType): bool;

    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO;
}
