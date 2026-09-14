<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\ServiceInterface\CommissionCalculationServiceInterface;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;

final class CommissionCalculationService implements CommissionCalculationServiceInterface
{
    public function __construct(private readonly CommissionCalculationEngineInterface $engine)
    {
    }

    public function calculate(CommissionCalculationRequestDTO $request): CommissionCalculationResultDTO
    {
        $basis = new CommissionBasisValueObject(
            economicEventReference: $request->economicEventReference,
            money: new CommissionMoneyValueObject($request->currencyCode, $request->basisMinorAmount),
            attributionReference: $request->attributionReference,
        );

        $rate = new CommissionRateInputDTO(
            rateType: CommissionRateTypeEnum::Percentage->value,
            percentageRate: '10',
            currencyCode: $request->currencyCode,
        );

        $result = $this->engine->calculate($basis, $rate);

        return new CommissionCalculationResultDTO(
            economicEventReference: $request->economicEventReference,
            currencyCode: $request->currencyCode,
            basisMinorAmount: $request->basisMinorAmount,
            commissionMinorAmount: $result->commissionMinorAmount,
            status: 'calculated',
        );
    }
}
