<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\ServiceInterface\CommissionCalculationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CommissionCalculationController extends AbstractController
{
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
