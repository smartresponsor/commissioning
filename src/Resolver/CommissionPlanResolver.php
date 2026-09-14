<?php

declare(strict_types=1);

namespace App\Commissioning\Resolver;

use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionPlanResolutionResultDTO;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\ResolverInterface\CommissionPlanResolverInterface;

final class CommissionPlanResolver implements CommissionPlanResolverInterface
{
    public function __construct(private readonly CommissionPlanRepositoryInterface $planRepository)
    {
    }

    public function resolve(CommissionPlanResolutionRequestDTO $request): CommissionPlanResolutionResultDTO
    {
        $planCode = $request->planCode ?: (string) ($request->context['commission_plan_code'] ?? 'default');
        $plan = $this->planRepository->findOneByCode($planCode);

        if ($plan instanceof CommissionPlanEntity && $plan->isActive()) {
            return new CommissionPlanResolutionResultDTO(
                planCode: $plan->getCode(),
                planName: $plan->getName(),
            );
        }

        return new CommissionPlanResolutionResultDTO(
            planCode: $planCode,
            planName: sprintf('Commission plan %s', $planCode),
        );
    }
}
