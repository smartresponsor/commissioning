<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionRateResolverInterface to typed application collaborators.
 */
interface CommissionRateResolverInterface
{
    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
    public function resolve(CommissionRateResolutionRequestDTO $request): CommissionRateInputDTO;
}
