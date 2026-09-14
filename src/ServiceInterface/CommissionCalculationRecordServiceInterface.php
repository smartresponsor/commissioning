<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;

interface CommissionCalculationRecordServiceInterface
{
    public function record(CommissionRecordCalculationRequestDTO $request): CommissionRecordCalculationResultDTO;
}
