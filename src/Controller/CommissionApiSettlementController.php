<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionApiErrorResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchExportResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyResponseDTO;
use App\Commissioning\Exception\CommissionApiRequestMappingException;
use App\Commissioning\ServiceInterface\CommissionApiJsonRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionApiRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchExportServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementReadinessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CommissionApiSettlementController extends AbstractController
{
    #[Route('/api/commissioning/ledger/settlement/ready', name: 'commissioning_api_settlement_ready', methods: ['POST'])]
    public function markSettlementReady(
        Request $request,
        CommissionApiJsonRequestMappingServiceInterface $jsonRequestMapper,
        CommissionApiRequestMappingServiceInterface $mappingService,
        CommissionSettlementReadinessServiceInterface $service,
    ): JsonResponse {
        try {
            /** @var CommissionApiSettlementReadyRequestDTO $apiRequest */
            $apiRequest = $jsonRequestMapper->map($request, CommissionApiSettlementReadyRequestDTO::class);
        } catch (CommissionApiRequestMappingException $exception) {
            return $this->json(new CommissionApiErrorResponseDTO($exception->getMessage(), $exception->getViolations()), 422);
        }

        $result = $service->markReadyForBeneficiary(
            $mappingService->mapSettlementReadyRequest($apiRequest),
        );

        return $this->json(new CommissionApiSettlementReadyResponseDTO($result->markedCount));
    }

    #[Route('/api/commissioning/settlement/batch', name: 'commissioning_api_settlement_batch_create', methods: ['POST'])]
    public function createBatch(
        Request $request,
        CommissionApiJsonRequestMappingServiceInterface $jsonRequestMapper,
        CommissionApiRequestMappingServiceInterface $mappingService,
        CommissionSettlementBatchServiceInterface $service,
    ): JsonResponse {
        try {
            /** @var CommissionApiSettlementBatchCreateRequestDTO $apiRequest */
            $apiRequest = $jsonRequestMapper->map($request, CommissionApiSettlementBatchCreateRequestDTO::class);
        } catch (CommissionApiRequestMappingException $exception) {
            return $this->json(new CommissionApiErrorResponseDTO($exception->getMessage(), $exception->getViolations()), 422);
        }

        $result = $service->createBatch($mappingService->mapSettlementBatchCreateRequest($apiRequest));

        return $this->json(new CommissionApiSettlementBatchCreateResponseDTO(
            batchReference: $result->batchReference,
            status: $result->status,
            entryCount: $result->entryCount,
            totalMinorAmount: $result->totalMinorAmount,
            duplicateEntryCount: $result->duplicateEntryCount,
        ));
    }

    #[Route('/api/commissioning/settlement/batch/export/{token}', name: 'commissioning_api_settlement_batch_export', methods: ['GET'])]
    public function exportBatch(
        string $token,
        CommissionSettlementBatchExportServiceInterface $service,
    ): JsonResponse {
        $result = $service->exportBatch($token);

        return $this->json(new CommissionApiSettlementBatchExportResponseDTO(
            batchReference: $result->batchReference,
            status: $result->status,
            entryCount: $result->entryCount,
            totalMinorAmount: $result->totalMinorAmount,
            entries: $result->entries,
        ));
    }
}
