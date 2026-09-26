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

/**
 * Calculates canonical Commissioning amounts through CommissionPercentageCalculator using typed basis and rate inputs.
 */
final class CommissionPercentageCalculator implements CommissionCalculatorInterface
{
    /**
     * Reports whether this calculator supports the supplied canonical Commissioning rate definition.
     */
    public function supports(string $rateType): bool
    {
        return CommissionRateTypeEnum::Percentage->value === $rateType;
    }

    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO {
        $percentage = (float) ($rate->percentageRate ?? '0');
        $amount = (int) round($basis->money->minorAmount * ($percentage / 100));

        return new CommissionCalculationEngineResultDTO(
            currencyCode: $basis->money->currencyCode,
            basisMinorAmount: $basis->money->minorAmount,
            commissionMinorAmount: $amount,
            lines: [
                new CommissionCalculationLineResultDTO(
                    lineType: CommissionCalculationLineTypeEnum::PercentageCommission->value,
                    currencyCode: $basis->money->currencyCode,
                    minorAmount: $amount,
                    explanation: sprintf('Percentage commission at %s%%.', $rate->percentageRate ?? '0'),
                ),
            ],
        );
    }
}
