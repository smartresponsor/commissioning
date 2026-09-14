<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;

interface CommissionCalculationServiceInterface
{
    public function calculate(CommissionCalculationRequestDTO $request): CommissionCalculationResultDTO;
}
