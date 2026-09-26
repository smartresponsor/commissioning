<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\ServiceInterface\CommissionCalculationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exposes Commissioning HTTP behavior through CommissionCalculationController while delegating business work to typed services.
 */
final class CommissionCalculationController extends AbstractController
{
    /**
     * Performs the preview operation defined by this typed Commissioning application contract.
     */
    #[Route('/commissioning/calculation/preview', name: 'commissioning_calculation_preview', methods: ['GET'])]
    public function preview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionCalculationServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        $result = $service->calculate(new CommissionCalculationRequestDTO(
            planCode: 'default',
            economicEventReference: 'preview',
            currencyCode: 'USD',
            basisMinorAmount: 10000,
        ));

        return $this->json($result);
    }
}
