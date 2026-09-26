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
 * Calculates canonical Commissioning amounts through CommissionTieredCalculator using typed basis and rate inputs.
 */
final class CommissionTieredCalculator implements CommissionCalculatorInterface
{
    /**
     * Reports whether this calculator supports the supplied canonical Commissioning rate definition.
     */
    public function supports(string $rateType): bool
    {
        return CommissionRateTypeEnum::Tiered->value === $rateType;
    }

    /**
     * Calculates the Commissioning result from the supplied typed request and configured calculation inputs.
     */
    public function calculate(
        CommissionBasisValueObject $basis,
        CommissionRateInputDTO $rate,
    ): CommissionCalculationEngineResultDTO {
        $selectedTier = null;

        foreach ($rate->tiers as $tier) {
            $minimum = $tier['minimumMinorAmount'];
            $maximum = $tier['maximumMinorAmount'] ?? null;

            if ($basis->money->minorAmount >= $minimum && (null === $maximum || $basis->money->minorAmount <= $maximum)) {
                $selectedTier = $tier;
                break;
            }
        }

        $percentage = (float) ($selectedTier['percentageRate'] ?? '0');
        $amount = (int) round($basis->money->minorAmount * ($percentage / 100));

        return new CommissionCalculationEngineResultDTO(
            currencyCode: $basis->money->currencyCode,
            basisMinorAmount: $basis->money->minorAmount,
            commissionMinorAmount: $amount,
            lines: [
                new CommissionCalculationLineResultDTO(
                    lineType: CommissionCalculationLineTypeEnum::TierAdjustment->value,
                    currencyCode: $basis->money->currencyCode,
                    minorAmount: $amount,
                    explanation: sprintf('Tiered commission at %s%%.', (string) $percentage),
                ),
            ],
        );
    }
}
