<?php

declare(strict_types=1);

namespace App\Commissioning\Command;

use App\Commissioning\ServiceInterface\CommissionRuntimeAuditServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'commissioning:runtime:audit',
    description: 'Runs read-only Commissioning runtime diagnostics.',
)]
final class CommissionRuntimeAuditCommand extends Command
{
    public function __construct(private readonly CommissionRuntimeAuditServiceInterface $runtimeAuditService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $report = $this->runtimeAuditService->audit();
        $io = new SymfonyStyle($input, $output);

        $io->title(sprintf('Commissioning runtime audit: %s', strtoupper($report->status)));

        foreach ($report->checks as $check) {
            $io->section(sprintf('%s: %s', $check->nameEntity, strtoupper($check->status)));
            foreach ($check->messages as $message) {
                $io->writeln(sprintf('- %s', $message));
            }
        }

        return 'pass' === $report->status ? Command::SUCCESS : Command::FAILURE;
    }
}
