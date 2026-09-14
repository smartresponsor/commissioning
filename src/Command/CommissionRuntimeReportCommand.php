<?php

declare(strict_types=1);

namespace App\Commissioning\Command;

use App\Commissioning\DTO\CommissionRuntimeCheckResultDTO;
use App\Commissioning\ServiceInterface\CommissionRuntimeAuditServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'commissioning:runtime:report',
    description: 'Prints a machine-readable JSON Commissioning runtime report.',
)]
final class CommissionRuntimeReportCommand extends Command
{
    public function __construct(private readonly CommissionRuntimeAuditServiceInterface $runtimeAuditService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $report = $this->runtimeAuditService->audit();

        $payload = [
            'component' => $report->component,
            'status' => $report->status,
            'checks' => array_map(
                static fn (CommissionRuntimeCheckResultDTO $check): array => [
                    'nameEntity' => $check->nameEntity,
                    'status' => $check->status,
                    'messages' => $check->messages,
                ],
                $report->checks,
            ),
        ];

        $output->writeln((string) json_encode($payload, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

        return 'pass' === $report->status ? Command::SUCCESS : Command::FAILURE;
    }
}
