<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;
use App\Commissioning\ResolverInterface\CommissionAttributionResolverInterface;
use App\Commissioning\ResolverInterface\CommissionBeneficiaryResolverInterface;
use App\Commissioning\ResolverInterface\CommissionPlanResolverInterface;
use App\Commissioning\ResolverInterface\CommissionRateResolverInterface;
use App\Commissioning\ServiceInterface\CommissionCalculationRecordServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventRecordServiceInterface;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;

final class CommissionEconomicEventRecordService implements CommissionEconomicEventRecordServiceInterface
{
    public function __construct(
        private readonly CommissionPlanResolverInterface $planResolver,
        private readonly CommissionRateResolverInterface $rateResolver,
        private readonly CommissionAttributionResolverInterface $attributionResolver,
        private readonly CommissionBeneficiaryResolverInterface $beneficiaryResolver,
        private readonly CommissionCalculationEngineInterface $engine,
        private readonly CommissionCalculationRecordServiceInterface $recordService,
    ) {
    }

    public function recordEconomicEvent(CommissionEconomicEventDTO $event): CommissionRecordCalculationResultDTO
    {
        $attribution = $this->attributionResolver->resolve(new CommissionAttributionResolutionRequestDTO(
            economicEventReference: $event->eventReference,
            sourceType: $event->attributionSourceType,
            sourceReference: $event->attributionSourceReference,
            context: $event->context,
        ));

        $context = array_merge($event->context, [
            'beneficiary_reference' => $attribution->sourceReference,
            'beneficiary_type' => $attribution->sourceType,
        ]);

        $beneficiary = $this->beneficiaryResolver->resolve(new CommissionRuleContextValueObject($context));

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

        $result = $this->engine->calculate(
            new CommissionBasisValueObject(
                economicEventReference: $event->eventReference,
                money: new CommissionMoneyValueObject($event->currencyCode, $event->basisMinorAmount),
                attributionReference: $attribution->sourceReference,
            ),
            $rate,
        );

        return $this->recordService->record(new CommissionRecordCalculationRequestDTO(
            planCode: $plan->planCode,
            planName: $plan->planName,
            economicEventReference: $event->eventReference,
            currencyCode: $event->currencyCode,
            basisMinorAmount: $event->basisMinorAmount,
            commissionMinorAmount: $result->commissionMinorAmount,
            beneficiaryReference: $beneficiary->beneficiaryReference,
            lines: $result->lines,
        ));
    }
}
