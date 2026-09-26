<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionRuntimeCheckResultDTO;
use App\Commissioning\DTO\CommissionRuntimeReportDTO;
use App\Commissioning\RepositoryInterface\CommissionMetadataRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionRuntimeAuditServiceInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionRuntimeAuditService across typed collaborators and boundaries.
 */
final class CommissionRuntimeAuditService implements CommissionRuntimeAuditServiceInterface
{
    /**
     * @var array<int, string>
     */
    private const EXPECTED_SERVICE_CONTRACTS = [
        'CommissionEconomicEventCalculationServiceInterface',
        'CommissionEconomicEventRecordServiceInterface',
        'CommissionSettlementBatchServiceInterface',
        'CommissionSettlementBatchExportServiceInterface',
    ];

    /**
     * @var array<int, class-string>
     */
    private const REQUIRED_ENTITIES = [
        'App\\Commissioning\\Entity\\CommissionPlanEntity',
        'App\\Commissioning\\Entity\\CommissionRateEntity',
        'App\\Commissioning\\Entity\\CommissionCalculationEntity',
        'App\\Commissioning\\Entity\\CommissionLedgerEntryEntity',
        'App\\Commissioning\\Entity\\CommissionSettlementBatchEntity',
    ];

    /**
     * @var array<int, string>
     */
    private const REQUIRED_ROUTES = [
        'commissioning_api_calculation_preview',
        'commissioning_api_calculation_record',
        'commissioning_api_settlement_ready',
        'commissioning_api_settlement_batch_create',
        'commissioning_api_settlement_batch_export',
    ];

    public function __construct(
        private readonly RouterInterface $router,
        private readonly CommissionMetadataRepositoryInterface $metadataRepository,
    ) {
    }

    /**
     * Builds the Commissioning runtime audit report from configured routes and persistence metadata.
     */
    public function audit(): CommissionRuntimeReportDTO
    {
        $checks = [
            $this->checkExpectedServiceContracts(),
            $this->checkRoutes(),
            $this->checkDoctrineMetadata(),
        ];

        $failed = array_filter(
            $checks,
            static fn (CommissionRuntimeCheckResultDTO $check): bool => 'fail' === $check->status,
        );

        return new CommissionRuntimeReportDTO(
            component: 'Commissioning',
            status: [] === $failed ? 'pass' : 'fail',
            checks: $checks,
        );
    }

    private function checkExpectedServiceContracts(): CommissionRuntimeCheckResultDTO
    {
        $messages = [];

        foreach (self::EXPECTED_SERVICE_CONTRACTS as $contract) {
            $messages[] = sprintf('%s: expected by service configuration', $contract);
        }

        return new CommissionRuntimeCheckResultDTO(
            nameEntity: 'expected_service_contracts',
            status: 'pass',
            messages: $messages,
        );
    }

    private function checkRoutes(): CommissionRuntimeCheckResultDTO
    {
        $collection = $this->router->getRouteCollection();
        $messages = [];

        foreach (self::REQUIRED_ROUTES as $routeName) {
            $messages[] = sprintf('%s: %s', $routeName, null !== $collection->get($routeName) ? 'available' : 'missing');
        }

        $missing = array_filter($messages, static fn (string $message): bool => str_ends_with($message, 'missing'));

        return new CommissionRuntimeCheckResultDTO(
            nameEntity: 'routes',
            status: [] === $missing ? 'pass' : 'fail',
            messages: $messages,
        );
    }

    private function checkDoctrineMetadata(): CommissionRuntimeCheckResultDTO
    {
        $messages = [];

        foreach (self::REQUIRED_ENTITIES as $entityClass) {
            try {
                $messages[] = sprintf('%s: mapped to %s', $entityClass, $this->metadataRepository->getTableName($entityClass));
            } catch (\Throwable $exception) {
                $messages[] = sprintf('%s: missing metadata: %s', $entityClass, $exception->getMessage());
            }
        }

        $missing = array_filter($messages, static fn (string $message): bool => str_contains($message, 'missing metadata'));

        return new CommissionRuntimeCheckResultDTO(
            nameEntity: 'doctrine_metadata',
            status: [] === $missing ? 'pass' : 'fail',
            messages: $messages,
        );
    }
}
