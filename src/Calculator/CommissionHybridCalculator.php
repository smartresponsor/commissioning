<?php

declare(strict_types=1);

namespace App\Commissioning\Calculator;

use App\Commissioning\CalculatorInterface\CommissionCalculatorInterface;
use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionCalculationLineResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\ValueObject\CommissionBasisValueObject;

final class CommissionHybridCalculator implements CommissionCalculatorInterface
{
    public function supports(string $rateType): bool
    {
        return CommissionRateTypeEnum::Hybrid->value === $rateType;
    }

    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO {
        $percentage = (float) ($rate->percentageRate ?? '0');
        $percentageAmount = (int) round($basis->money->minorAmount * ($percentage / 100));
        $fixedAmount = $rate->fixedMinorAmount ?? 0;
        $total = $percentageAmount + $fixedAmount;

        return new CommissionCalculationEngineResultDTO(
            currencyCode: $basis->money->currencyCode,
            basisMinorAmount: $basis->money->minorAmount,
            commissionMinorAmount: $total,
            lines: [
                new CommissionCalculationLineResultDTO(
                    lineType: CommissionCalculationLineTypeEnum::PercentageCommission->value,
                    currencyCode: $basis->money->currencyCode,
                    minorAmount: $percentageAmount,
                    explanation: sprintf('Hybrid percentage part at %s%%.', $rate->percentageRate ?? '0'),
                ),
                new CommissionCalculationLineResultDTO(
                    lineType: CommissionCalculationLineTypeEnum::FixedCommission->value,
                    currencyCode: $basis->money->currencyCode,
                    minorAmount: $fixedAmount,
                    explanation: 'Hybrid fixed part.',
                ),
            ],
        );
    }
}
