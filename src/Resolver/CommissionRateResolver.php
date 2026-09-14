<?php

declare(strict_types=1);

namespace App\Commissioning\Resolver;

use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use App\Commissioning\ResolverInterface\CommissionRateResolverInterface;

final class CommissionRateResolver implements CommissionRateResolverInterface
{
    public function __construct(
        private readonly CommissionRateRepositoryInterface $rateRepository,
        private readonly CommissionTierRepositoryInterface $tierRepository,
    ) {
    }

    public function resolve(CommissionRateResolutionRequestDTO $request): CommissionRateInputDTO
    {
        $rates = $this->rateRepository->findActiveByPlanCode($request->planCode, $request->currencyCode);

        foreach ($rates as $rate) {
            if (!$rate instanceof CommissionRateEntity) {
                continue;
            }

            $tiers = [];
            foreach ($this->tierRepository->findByRate($rate) as $tier) {
                $tiers[] = [
                    'minimumMinorAmount' => $tier->getMinimumMinorAmount(),
                    'maximumMinorAmount' => $tier->getMaximumMinorAmount(),
                    'percentageRate' => $tier->getPercentageRate(),
                ];
            }

            return new CommissionRateInputDTO(
                rateType: $rate->getType()->value,
                percentageRate: $rate->getPercentageRate(),
                fixedMinorAmount: $rate->getFixedMinorAmount(),
                currencyCode: $rate->getCurrencyCode() ?? $request->currencyCode,
                tiers: $tiers,
            );
        }

        return $this->fallbackRate($request);
    }

    private function fallbackRate(CommissionRateResolutionRequestDTO $request): CommissionRateInputDTO
    {
        $rateType = (string) ($request->context['commission_rate_type'] ?? CommissionRateTypeEnum::Percentage->value);
        $percentageRate = isset($request->context['commission_percentage_rate'])
            ? (string) $request->context['commission_percentage_rate']
            : '10';

        $fixedMinorAmount = isset($request->context['commission_fixed_minor_amount'])
            ? (int) $request->context['commission_fixed_minor_amount']
            : null;

        $tiers = [];

        if (CommissionRateTypeEnum::Tiered->value === $rateType) {
            $tiers = [
                [
                    'minimumMinorAmount' => 0,
                    'maximumMinorAmount' => 99999,
                    'percentageRate' => '5',
                ],
                [
                    'minimumMinorAmount' => 100000,
                    'maximumMinorAmount' => null,
                    'percentageRate' => '8',
                ],
            ];
        }

        return new CommissionRateInputDTO(
            rateType: $rateType,
            percentageRate: $percentageRate,
            fixedMinorAmount: $fixedMinorAmount,
            currencyCode: $request->currencyCode,
            tiers: $tiers,
        );
    }
}
