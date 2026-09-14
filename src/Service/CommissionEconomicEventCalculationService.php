<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\ResolverInterface\CommissionAttributionResolverInterface;
use App\Commissioning\ResolverInterface\CommissionPlanResolverInterface;
use App\Commissioning\ResolverInterface\CommissionRateResolverInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventCalculationServiceInterface;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;

final class CommissionEconomicEventCalculationService implements CommissionEconomicEventCalculationServiceInterface
{
    public function __construct(
        private readonly CommissionPlanResolverInterface $planResolver,
        private readonly CommissionRateResolverInterface $rateResolver,
        private readonly CommissionAttributionResolverInterface $attributionResolver,
        private readonly CommissionCalculationEngineInterface $engine,
    ) {
    }

    public function calculateForEconomicEvent(CommissionEconomicEventDTO $event): CommissionCalculationResultDTO
    {
        $attribution = $this->attributionResolver->resolve(new CommissionAttributionResolutionRequestDTO(
            economicEventReference: $event->eventReference,
            sourceType: $event->attributionSourceType,
            sourceReference: $event->attributionSourceReference,
            context: $event->context,
        ));

        $plan = $this->planResolver->resolve(new CommissionPlanResolutionRequestDTO(
            planCode: $event->planCode,
            context: $event->context,
        ));

        $rate = $this->rateResolver->resolve(new CommissionRateResolutionRequestDTO(
            planCode: $plan->planCode,
            currencyCode: $event->currencyCode,
            basisMinorAmount: $event->basisMinorAmount,
            context: $event->context,
        ));

        $basis = new CommissionBasisValueObject(
            economicEventReference: $event->eventReference,
            money: new CommissionMoneyValueObject($event->currencyCode, $event->basisMinorAmount),
            attributionReference: $attribution->sourceReference,
        );

        $result = $this->engine->calculate($basis, $rate);

        return new CommissionCalculationResultDTO(
            economicEventReference: $event->eventReference,
            currencyCode: $event->currencyCode,
            basisMinorAmount: $event->basisMinorAmount,
            commissionMinorAmount: $result->commissionMinorAmount,
            status: 'calculated',
        );
    }
}
