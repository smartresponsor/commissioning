<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventCalculationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CommissionEconomicEventCalculationController extends AbstractController
{
    #[Route('/commissioning/economic/event/preview', name: 'commissioning_economic_event_preview', methods: ['GET'])]
    public function preview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionEconomicEventCalculationServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        $result = $service->calculateForEconomicEvent(new CommissionEconomicEventDTO(
            eventReference: 'economic-event-preview',
            currencyCode: 'USD',
            basisMinorAmount: 25000,
            planCode: 'default',
            attributionSourceType: 'partner',
            attributionSourceReference: 'partner-preview',
            context: [
                'commission_rate_type' => 'percentage',
                'commission_percentage_rate' => '12.5',
            ],
        ));

        return $this->json($result);
    }
}
