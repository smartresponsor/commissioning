<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionApiRequestMappingServiceInterface to typed application collaborators.
 */
interface CommissionApiRequestMappingServiceInterface
{
    /**
     * Maps the supplied external Commissioning request into its canonical typed application representation.
     */
    public function mapCalculationRequest(CommissionApiCalculationRequestDTO $request): CommissionEconomicEventDTO;

    /**
     * Maps the supplied external Commissioning request into its canonical typed application representation.
     */
    public function mapSettlementReadyRequest(CommissionApiSettlementReadyRequestDTO $request): string;

    /**
     * Maps the supplied external Commissioning request into its canonical typed application representation.
     */
    public function mapSettlementBatchCreateRequest(
        CommissionApiSettlementBatchCreateRequestDTO $request,
    ): CommissionSettlementBatchCreateRequestDTO;
}
