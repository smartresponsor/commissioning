<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;

interface CommissionApiRequestMappingServiceInterface
{
    public function mapCalculationRequest(CommissionApiCalculationRequestDTO $request): CommissionEconomicEventDTO;

    public function mapSettlementReadyRequest(CommissionApiSettlementReadyRequestDTO $request): string;

    public function mapSettlementBatchCreateRequest(
        CommissionApiSettlementBatchCreateRequestDTO $request,
    ): CommissionSettlementBatchCreateRequestDTO;
}
