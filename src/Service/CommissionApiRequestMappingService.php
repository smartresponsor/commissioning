<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\ServiceInterface\CommissionApiRequestMappingServiceInterface;

final class CommissionApiRequestMappingService implements CommissionApiRequestMappingServiceInterface
{
    public function mapCalculationRequest(CommissionApiCalculationRequestDTO $request): CommissionEconomicEventDTO
    {
        return new CommissionEconomicEventDTO(
            eventReference: $request->eventReference,
            currencyCode: $request->currencyCode,
            basisMinorAmount: $request->basisMinorAmount,
            planCode: $request->planCode,
            attributionSourceType: $request->attributionSourceType,
            attributionSourceReference: $request->attributionSourceReference,
            context: $request->context,
        );
    }

    public function mapSettlementReadyRequest(CommissionApiSettlementReadyRequestDTO $request): string
    {
        return $request->beneficiaryReference;
    }

    public function mapSettlementBatchCreateRequest(
        CommissionApiSettlementBatchCreateRequestDTO $request,
    ): CommissionSettlementBatchCreateRequestDTO {
        return new CommissionSettlementBatchCreateRequestDTO(
            batchReference: $request->batchReference,
            beneficiaryReference: $request->beneficiaryReference,
        );
    }
}
