<?php

declare(strict_types=1);

namespace App\Commissioning\Resolver;

use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionPlanResolutionResultDTO;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\ResolverInterface\CommissionPlanResolverInterface;

/**
 * Resolves canonical Commissioning data through CommissionPlanResolver from typed requests and available context.
 */
final class CommissionPlanResolver implements CommissionPlanResolverInterface
{
    public function __construct(private readonly CommissionPlanRepositoryInterface $planRepository)
    {
    }

    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
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
