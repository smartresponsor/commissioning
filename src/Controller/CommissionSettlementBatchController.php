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

final class CommissionSettlementBatchController extends AbstractController
{
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

    #[Route('/commissioning/settlement/batch/export/preview', name: 'commissioning_settlement_batch_export_preview', methods: ['GET'])]
    public function exportPreview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionSettlementBatchExportServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        return $this->json($service->exportBatch('commission-settlement-preview'));
    }
}
