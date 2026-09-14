<?php

declare(strict_types=1);

namespace App\Commissioning\Controller;

use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventRecordServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CommissionEconomicEventRecordController extends AbstractController
{
    #[Route('/commissioning/economic/event/record/preview', name: 'commissioning_economic_event_record_preview', methods: ['POST', 'GET'])]
    public function recordPreview(
        CommissionDemoRouteGuardServiceInterface $demoRouteGuard,
        CommissionEconomicEventRecordServiceInterface $service,
    ): JsonResponse {
        $demoRouteGuard->assertDemoRouteAllowed();

        $result = $service->recordEconomicEvent(new CommissionEconomicEventDTO(
            eventReference: 'economic-event-record-preview',
            currencyCode: 'USD',
            basisMinorAmount: 30000,
            planCode: 'default',
            attributionSourceType: 'partner',
            attributionSourceReference: 'partner-preview',
            context: [
                'commission_rate_type' => 'hybrid',
                'commission_percentage_rate' => '7.5',
                'commission_fixed_minor_amount' => 250,
            ],
        ));

        return $this->json($result);
    }
}
