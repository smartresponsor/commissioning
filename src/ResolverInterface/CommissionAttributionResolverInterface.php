<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionAttributionResolutionResultDTO;

interface CommissionAttributionResolverInterface
{
    public function resolve(CommissionAttributionResolutionRequestDTO $request): CommissionAttributionResolutionResultDTO;
}
