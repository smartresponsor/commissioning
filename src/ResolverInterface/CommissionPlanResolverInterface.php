<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionPlanResolutionResultDTO;

interface CommissionPlanResolverInterface
{
    public function resolve(CommissionPlanResolutionRequestDTO $request): CommissionPlanResolutionResultDTO;
}
