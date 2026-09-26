<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Controller;

use App\Commissioning\Controller\CommissionApiCalculationController;
use App\Commissioning\Controller\CommissionApiSettlementController;
use App\Commissioning\Controller\CommissionCalculationController;
use App\Commissioning\Controller\CommissionEconomicEventCalculationController;
use App\Commissioning\Controller\CommissionEconomicEventRecordController;
use App\Commissioning\Controller\CommissionSettlementBatchController;
use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateResultDTO;
use App\Commissioning\DTO\CommissionSettlementBatchExportDTO;
use App\Commissioning\DTO\CommissionSettlementEntryExportDTO;
use App\Commissioning\DTO\CommissionSettlementReadinessResultDTO;
use App\Commissioning\ServiceInterface\CommissionApiJsonRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionApiRequestMappingServiceInterface;
use App\Commissioning\ServiceInterface\CommissionCalculationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventCalculationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionEconomicEventRecordServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchExportServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchServiceInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementReadinessServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

final class CommissionControllerContractTest extends TestCase
{
    public function testApiCalculationControllersReturnMappedSuccessResponses(): void
    {
        $apiRequest = new CommissionApiCalculationRequestDTO('event-1', 'USD', 10_000);
        $event = new CommissionEconomicEventDTO('event-1', 'USD', 10_000);
        $mapper = $this->createMock(CommissionApiJsonRequestMappingServiceInterface::class);
        $mapper->expects(self::exactly(2))->method('map')->willReturn($apiRequest);
        $requestMapper = $this->createMock(CommissionApiRequestMappingServiceInterface::class);
        $requestMapper->expects(self::exactly(2))->method('mapCalculationRequest')->with($apiRequest)->willReturn($event);

        $calculationService = $this->createMock(CommissionEconomicEventCalculationServiceInterface::class);
        $calculationService->expects(self::once())->method('calculateForEconomicEvent')->with($event)
            ->willReturn(new CommissionCalculationResultDTO('event-1', 'USD', 10_000, 750, 'calculated'));

        $recordService = $this->createMock(CommissionEconomicEventRecordServiceInterface::class);
        $recordService->expects(self::once())->method('recordEconomicEvent')->with($event)
            ->willReturn(new CommissionRecordCalculationResultDTO('event-1', 'USD', 750, 'pending'));

        $controller = $this->controller(new CommissionApiCalculationController());
        $request = Request::create('/api/commissioning/calculation', 'POST', content: '{}');

        $preview = $controller->preview($request, $mapper, $requestMapper, $calculationService);
        $record = $controller->record($request, $mapper, $requestMapper, $recordService);

        self::assertSame(200, $preview->getStatusCode());
        self::assertStringContainsString('"commissionMinorAmount":750', (string) $preview->getContent());
        self::assertSame(200, $record->getStatusCode());
        self::assertStringContainsString('"ledgerStatus":"pending"', (string) $record->getContent());
    }

    public function testApiSettlementControllerReturnsSuccessResponses(): void
    {
        $readyRequest = new CommissionApiSettlementReadyRequestDTO('beneficiary-1');
        $batchApiRequest = new CommissionApiSettlementBatchCreateRequestDTO('batch-1', 'beneficiary-1');
        $batchRequest = new CommissionSettlementBatchCreateRequestDTO('batch-1', 'beneficiary-1');

        $jsonMapper = $this->createMock(CommissionApiJsonRequestMappingServiceInterface::class);
        $jsonMapper->expects(self::exactly(2))->method('map')->willReturnOnConsecutiveCalls($readyRequest, $batchApiRequest);

        $requestMapper = $this->createMock(CommissionApiRequestMappingServiceInterface::class);
        $requestMapper->expects(self::once())->method('mapSettlementReadyRequest')->with($readyRequest)->willReturn('beneficiary-1');
        $requestMapper->expects(self::once())->method('mapSettlementBatchCreateRequest')->with($batchApiRequest)->willReturn($batchRequest);

        $readiness = $this->createMock(CommissionSettlementReadinessServiceInterface::class);
        $readiness->expects(self::once())->method('markReadyForBeneficiary')->with('beneficiary-1')
            ->willReturn(new CommissionSettlementReadinessResultDTO(2));

        $batchService = $this->createMock(CommissionSettlementBatchServiceInterface::class);
        $batchService->expects(self::once())->method('createBatch')->with($batchRequest)
            ->willReturn(new CommissionSettlementBatchCreateResultDTO('batch-1', 'draft', 2, 1_000, 0));

        $entry = new CommissionSettlementEntryExportDTO('beneficiary-1', 'USD', 1_000, 'settlement_ready');
        $exportService = $this->createMock(CommissionSettlementBatchExportServiceInterface::class);
        $exportService->expects(self::once())->method('exportBatch')->with('batch-1')
            ->willReturn(new CommissionSettlementBatchExportDTO('batch-1', 'exported', 1, 1_000, [$entry]));

        $controller = $this->controller(new CommissionApiSettlementController());
        $request = Request::create('/api/commissioning/settlement', 'POST', content: '{}');

        $ready = $controller->markSettlementReady($request, $jsonMapper, $requestMapper, $readiness);
        $batch = $controller->createBatch($request, $jsonMapper, $requestMapper, $batchService);
        $export = $controller->exportBatch('batch-1', $exportService);

        self::assertStringContainsString('"markedCount":2', (string) $ready->getContent());
        self::assertStringContainsString('"entryCount":2', (string) $batch->getContent());
        self::assertStringContainsString('"status":"exported"', (string) $export->getContent());
    }

    public function testDemoControllersDelegateToGuardAndServices(): void
    {
        $guard = $this->createMock(CommissionDemoRouteGuardServiceInterface::class);
        $guard->expects(self::exactly(5))->method('assertDemoRouteAllowed');

        $calculation = $this->createMock(CommissionCalculationServiceInterface::class);
        $calculation->expects(self::once())->method('calculate')
            ->willReturn(new CommissionCalculationResultDTO('preview', 'USD', 10_000, 500, 'calculated'));

        $economicCalculation = $this->createMock(CommissionEconomicEventCalculationServiceInterface::class);
        $economicCalculation->expects(self::once())->method('calculateForEconomicEvent')
            ->willReturn(new CommissionCalculationResultDTO('economic-event-preview', 'USD', 25_000, 3_125, 'calculated'));

        $record = $this->createMock(CommissionEconomicEventRecordServiceInterface::class);
        $record->expects(self::once())->method('recordEconomicEvent')
            ->willReturn(new CommissionRecordCalculationResultDTO('economic-event-record-preview', 'USD', 2_500, 'pending'));

        $batchService = $this->createMock(CommissionSettlementBatchServiceInterface::class);
        $batchService->expects(self::once())->method('createBatch')
            ->willReturn(new CommissionSettlementBatchCreateResultDTO('commission-settlement-preview', 'draft', 0, 0));

        $exportService = $this->createMock(CommissionSettlementBatchExportServiceInterface::class);
        $exportService->expects(self::once())->method('exportBatch')->with('commission-settlement-preview')
            ->willReturn(new CommissionSettlementBatchExportDTO('commission-settlement-preview', 'exported', 0, 0, []));

        self::assertSame(200, $this->controller(new CommissionCalculationController())->preview($guard, $calculation)->getStatusCode());
        self::assertSame(200, $this->controller(new CommissionEconomicEventCalculationController())->preview($guard, $economicCalculation)->getStatusCode());
        self::assertSame(200, $this->controller(new CommissionEconomicEventRecordController())->recordPreview($guard, $record)->getStatusCode());

        $settlementController = $this->controller(new CommissionSettlementBatchController());
        self::assertSame(200, $settlementController->createPreview($guard, $batchService)->getStatusCode());
        self::assertSame(200, $settlementController->exportPreview($guard, $exportService)->getStatusCode());
    }

    /**
     * Supplies the minimal controller container needed by AbstractController::json().
     */
    private function controller(object $controller): object
    {
        self::assertTrue(method_exists($controller, 'setContainer'));
        $controller->setContainer(new Container());

        return $controller;
    }
}
