<?php

declare(strict_types=1);

namespace App\Commissioning\ResolverInterface;

use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionAttributionResolutionResultDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionAttributionResolverInterface to typed application collaborators.
 */
interface CommissionAttributionResolverInterface
{
    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
    public function resolve(CommissionAttributionResolutionRequestDTO $request): CommissionAttributionResolutionResultDTO;
}
