<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionPlanResolutionResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionPlanResolverInterface to typed application collaborators.
 */
interface CommissionPlanResolverInterface
{
    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
    public function resolve(CommissionPlanResolutionRequestDTO $request): CommissionPlanResolutionResultDTO;
}
