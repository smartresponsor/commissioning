<?php

declare(strict_types=1);

namespace App\Commissioning\Resolver;

use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionAttributionResolutionResultDTO;
use App\Commissioning\Enum\CommissionAttributionSourceTypeEnum;
use App\Commissioning\ResolverInterface\CommissionAttributionResolverInterface;

/**
 * Resolves canonical Commissioning data through CommissionAttributionResolver from typed requests and available context.
 */
final class CommissionAttributionResolver implements CommissionAttributionResolverInterface
{
    /**
     * Resolves canonical Commissioning data from the supplied typed request and available application context.
     */
    public function resolve(CommissionAttributionResolutionRequestDTO $request): CommissionAttributionResolutionResultDTO
    {
        $sourceType = $request->sourceType
            ?: (string) ($request->context['attribution_source_type'] ?? CommissionAttributionSourceTypeEnum::Partner->value);

        $sourceReference = $request->sourceReference
            ?: (string) ($request->context['attribution_source_reference'] ?? 'unresolved');

        return new CommissionAttributionResolutionResultDTO(
            sourceType: $sourceType,
            sourceReference: $sourceReference,
            economicEventReference: $request->economicEventReference,
        );
    }
}
