<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionCalculationRecordServiceInterface to typed application collaborators.
 */
interface CommissionCalculationRecordServiceInterface
{
    /**
     * Performs the record operation defined by this typed Commissioning application contract.
     */
    public function record(CommissionRecordCalculationRequestDTO $request): CommissionRecordCalculationResultDTO;
}
