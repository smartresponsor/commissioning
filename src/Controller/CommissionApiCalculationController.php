<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiCalculationResponseDTO;
use App\Commissioning\DTO\CommissionApiErrorResponseDTO;
use App\Commissioning\DTO\CommissionApiRecordResponseDTO;
use App\Commissioning\Exception\CommissionApiRequestMappingException;
use App\Commissioning\ServiceInterface\CommissionApiJsonRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionApiRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventCalculationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventRecordServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CommissionApiCalculationController extends AbstractController
{
    #[Route('/api/commissioning/calculation/preview', name: 'commissioning_api_calculation_preview', methods: ['POST'])]
    public function preview(
        Request $request,
        CommissionApiJsonRequestMappingServiceInterface $jsonRequestMapper,
        CommissionApiRequestMappingServiceInterface $mappingService,
        CommissionEconomicEventCalculationServiceInterface $calculationService,
    ): JsonResponse {
        try {
            /** @var CommissionApiCalculationRequestDTO $apiRequest */
            $apiRequest = $jsonRequestMapper->map($request, CommissionApiCalculationRequestDTO::class);
        } catch (CommissionApiRequestMappingException $exception) {
            return $this->json(new CommissionApiErrorResponseDTO($exception->getMessage(), $exception->getViolations()), 422);
        }

        $result = $calculationService->calculateForEconomicEvent(
            $mappingService->mapCalculationRequest($apiRequest),
        );

        return $this->json(new CommissionApiCalculationResponseDTO(
            eventReference: $result->economicEventReference,
            currencyCode: $result->currencyCode,
            basisMinorAmount: $result->basisMinorAmount,
            commissionMinorAmount: $result->commissionMinorAmount,
            status: $result->status,
        ));
    }

    #[Route('/api/commissioning/calculation', name: 'commissioning_api_calculation_record', methods: ['POST'])]
    public function record(
        Request $request,
        CommissionApiJsonRequestMappingServiceInterface $jsonRequestMapper,
        CommissionApiRequestMappingServiceInterface $mappingService,
        CommissionEconomicEventRecordServiceInterface $recordService,
    ): JsonResponse {
        try {
            /** @var CommissionApiCalculationRequestDTO $apiRequest */
            $apiRequest = $jsonRequestMapper->map($request, CommissionApiCalculationRequestDTO::class);
        } catch (CommissionApiRequestMappingException $exception) {
            return $this->json(new CommissionApiErrorResponseDTO($exception->getMessage(), $exception->getViolations()), 422);
        }

        $result = $recordService->recordEconomicEvent(
            $mappingService->mapCalculationRequest($apiRequest),
        );

        return $this->json(new CommissionApiRecordResponseDTO(
            eventReference: $result->economicEventReference,
            currencyCode: $result->currencyCode,
            commissionMinorAmount: $result->commissionMinorAmount,
            ledgerStatus: $result->ledgerStatus,
            duplicate: $result->duplicate,
        ));
    }
}
