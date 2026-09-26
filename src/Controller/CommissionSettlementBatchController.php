<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchExportServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exposes Commissioning HTTP behavior through CommissionSettlementBatchController while delegating business work to typed services.
 */
final class CommissionSettlementBatchController extends AbstractController
{
    /**
     * Performs the createPreview operation defined by this typed Commissioning application contract.
     */
    #[Route('/commissioning/settlement/batch/create/preview', name: 'commissioning_settlement_batch_create_preview', methods: ['GET', 'POST'])]
    public function createPreview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionSettlementBatchServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        return $this->json($service->createBatch(new CommissionSettlementBatchCreateRequestDTO(
            batchReference: 'commission-settlement-preview',
        )));
    }

    /**
     * Exports canonical Commissioning settlement data through the typed application handoff contract.
     */
    #[Route('/commissioning/settlement/batch/export/preview', name: 'commissioning_settlement_batch_export_preview', methods: ['GET'])]
    public function exportPreview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionSettlementBatchExportServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        return $this->json($service->exportBatch('commission-settlement-preview'));
    }
}
