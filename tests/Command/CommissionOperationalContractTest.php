<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Command;

use App\Commissioning\Command\CommissionDevelopmentSeedCommand;
use App\Commissioning\Command\CommissionRouteAuditCommand;
use App\Commissioning\Command\CommissionRuntimeAuditCommand;
use App\Commissioning\Command\CommissionRuntimeReportCommand;
use App\Commissioning\Command\CommissionSchemaReadinessCommand;
use App\Commissioning\DTO\CommissionRuntimeCheckResultDTO;
use App\Commissioning\DTO\CommissionRuntimeReportDTO;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\RepositoryInterface\CommissionMetadataRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRuleRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use App\Commissioning\Service\CommissionDevelopmentSeedService;
use App\Commissioning\Service\CommissionRuntimeAuditService;
use App\Commissioning\ServiceInterface\CommissionDevelopmentSeedServiceInterface;
use App\Commissioning\ServiceInterface\CommissionRuntimeAuditServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class CommissionOperationalContractTest extends TestCase
{
    public function testDevelopmentSeedCommandReportsCreatedCounts(): void
    {
        $service = $this->createMock(CommissionDevelopmentSeedServiceInterface::class);
        $service->expects(self::once())->method('seedDefault')->willReturn([
            'createdPlans' => 1,
            'createdRates' => 2,
            'createdRules' => 1,
            'createdTiers' => 2,
        ]);

        $tester = new CommandTester(new CommissionDevelopmentSeedCommand($service));
        self::assertSame(Command::SUCCESS, $tester->execute([]));
        self::assertStringContainsString('Commissioning development seed completed', $tester->getDisplay());
        self::assertStringContainsString('Created rates', $tester->getDisplay());
    }

    public function testRouteAuditListsOnlyCommissioningRoutes(): void
    {
        $collection = new RouteCollection();
        $collection->add('commissioning_one', new Route('/commissioning/one', methods: ['GET']));
        $collection->add('unrelated', new Route('/other', methods: ['POST']));

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())->method('getRouteCollection')->willReturn($collection);

        $tester = new CommandTester(new CommissionRouteAuditCommand($router));
        self::assertSame(Command::SUCCESS, $tester->execute([]));
        self::assertStringContainsString('commissioning_one', $tester->getDisplay());
        self::assertStringNotContainsString('unrelated', $tester->getDisplay());
    }

    public function testRuntimeAuditAndReportCommandsReflectPassAndFailureStatus(): void
    {
        $passReport = new CommissionRuntimeReportDTO('Commissioning', 'pass', [
            new CommissionRuntimeCheckResultDTO('routes', 'pass', ['available']),
        ]);
        $failReport = new CommissionRuntimeReportDTO('Commissioning', 'fail', [
            new CommissionRuntimeCheckResultDTO('routes', 'fail', ['missing']),
        ]);

        $service = $this->createMock(CommissionRuntimeAuditServiceInterface::class);
        $service->expects(self::exactly(4))->method('audit')
            ->willReturnOnConsecutiveCalls($passReport, $failReport, $passReport, $failReport);

        $auditPass = new CommandTester(new CommissionRuntimeAuditCommand($service));
        self::assertSame(Command::SUCCESS, $auditPass->execute([]));
        self::assertStringContainsString('AVAILABLE', strtoupper($auditPass->getDisplay()));

        $auditFail = new CommandTester(new CommissionRuntimeAuditCommand($service));
        self::assertSame(Command::FAILURE, $auditFail->execute([]));

        $reportPass = new CommandTester(new CommissionRuntimeReportCommand($service));
        self::assertSame(Command::SUCCESS, $reportPass->execute([]));
        self::assertStringContainsString('"component": "Commissioning"', $reportPass->getDisplay());

        $reportFail = new CommandTester(new CommissionRuntimeReportCommand($service));
        self::assertSame(Command::FAILURE, $reportFail->execute([]));
        self::assertStringContainsString('"status": "fail"', $reportFail->getDisplay());
    }

    public function testSchemaReadinessReportsMappedAndMissingMetadata(): void
    {
        $metadata = $this->createMock(CommissionMetadataRepositoryInterface::class);
        $metadata->expects(self::exactly(11))->method('getTableName')->willReturn('commission_table');

        $green = new CommandTester(new CommissionSchemaReadinessCommand($metadata));
        self::assertSame(Command::SUCCESS, $green->execute([]));
        self::assertStringContainsString('mapped', $green->getDisplay());

        $missing = $this->createStub(CommissionMetadataRepositoryInterface::class);
        $missing->method('getTableName')->willThrowException(new \RuntimeException('metadata unavailable'));

        $red = new CommandTester(new CommissionSchemaReadinessCommand($missing));
        self::assertSame(Command::FAILURE, $red->execute([]));
        self::assertStringContainsString('metadata unavailable', $red->getDisplay());
    }

    public function testRuntimeAuditServiceReportsPassAndFailureDimensions(): void
    {
        $routes = new RouteCollection();
        foreach ([
            'commissioning_api_calculation_preview',
            'commissioning_api_calculation_record',
            'commissioning_api_settlement_ready',
            'commissioning_api_settlement_batch_create',
            'commissioning_api_settlement_batch_export',
        ] as $name) {
            $routes->add($name, new Route('/'.$name));
        }

        $router = $this->createStub(RouterInterface::class);
        $router->method('getRouteCollection')->willReturn($routes);
        $metadata = $this->createStub(CommissionMetadataRepositoryInterface::class);
        $metadata->method('getTableName')->willReturn('commission_table');

        $pass = (new CommissionRuntimeAuditService($router, $metadata))->audit();

        self::assertSame('pass', $pass->status);
        self::assertCount(3, $pass->checks);

        $missingRoutes = new RouteCollection();
        $badRouter = $this->createStub(RouterInterface::class);
        $badRouter->method('getRouteCollection')->willReturn($missingRoutes);
        $badMetadata = $this->createStub(CommissionMetadataRepositoryInterface::class);
        $badMetadata->method('getTableName')->willThrowException(new \RuntimeException('missing'));

        $fail = (new CommissionRuntimeAuditService($badRouter, $badMetadata))->audit();

        self::assertSame('fail', $fail->status);
    }

    public function testDevelopmentSeedCreatesMissingDefaultDataAndIsIdempotentWhenPresent(): void
    {
        $planRepository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $planRepository->expects(self::exactly(2))->method('findOneByCode')
            ->willReturnOnConsecutiveCalls(null, new CommissionPlanEntity('default', 'Default'));
        $planRepository->expects(self::once())->method('save');

        $rateRepository = $this->createMock(CommissionRateRepositoryInterface::class);
        $rateRepository->expects(self::exactly(2))->method('findActiveByPlanCode')
            ->willReturnOnConsecutiveCalls([], [new \stdClass()]);
        $rateRepository->expects(self::exactly(2))->method('save');

        $ruleRepository = $this->createMock(CommissionRuleRepositoryInterface::class);
        $ruleRepository->expects(self::exactly(2))->method('findActiveByPlanCodeOrdered')
            ->willReturnOnConsecutiveCalls([], [new \stdClass()]);
        $ruleRepository->expects(self::once())->method('save');

        $tierRepository = $this->createMock(CommissionTierRepositoryInterface::class);
        $tierRepository->expects(self::exactly(2))->method('save');

        $service = new CommissionDevelopmentSeedService(
            $planRepository,
            $rateRepository,
            $ruleRepository,
            $tierRepository,
        );

        self::assertSame([
            'createdPlans' => 1,
            'createdRates' => 2,
            'createdRules' => 1,
            'createdTiers' => 2,
        ], $service->seedDefault());

        self::assertSame([
            'createdPlans' => 0,
            'createdRates' => 0,
            'createdRules' => 0,
            'createdTiers' => 0,
        ], $service->seedDefault());
    }
}
