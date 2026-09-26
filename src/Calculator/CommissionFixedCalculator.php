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
 * Calculates canonical Commissioning amounts through CommissionFixedCalculator using typed basis and rate inputs.
 */
final class CommissionFixedCalculator implements CommissionCalculatorInterface
{
    /**
     * Reports whether this calculator supports the supplied canonical Commissioning rate definition.
     */
    public function supports(string $rateType): bool
    {
        return CommissionRateTypeEnum::Fixed->value === $rateType;
    }

    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO {
        $amount = $rate->fixedMinorAmount ?? 0;

        return new CommissionCalculationEngineResultDTO(
            currencyCode: $basis->money->currencyCode,
            basisMinorAmount: $basis->money->minorAmount,
            commissionMinorAmount: $amount,
            lines: [
                new CommissionCalculationLineResultDTO(
                    lineType: CommissionCalculationLineTypeEnum::FixedCommission->value,
                    currencyCode: $basis->money->currencyCode,
                    minorAmount: $amount,
                    explanation: 'Fixed commission amount.',
                ),
            ],
        );
    }
}
