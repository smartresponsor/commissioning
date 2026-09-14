<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;

interface CommissionRateResolverInterface
{
    public function resolve(CommissionRateResolutionRequestDTO $request): CommissionRateInputDTO;
}
