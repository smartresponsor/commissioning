<?php

declare(strict_types=1);

namespace App\Commissioning\Command;

use App\Commissioning\RepositoryInterface\CommissionMetadataRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'commissioning:schema:readiness',
    description: 'Checks read-only Doctrine metadata readiness for Commissioning entities.',
)]
/**
 * Exposes the Commissioning console operation implemented by CommissionSchemaReadinessCommand for deterministic operational workflows.
 */
final class CommissionSchemaReadinessCommand extends Command
{
    public function __construct(private readonly CommissionMetadataRepositoryInterface $metadataRepository)
    {
        parent::__construct();
    }

    /**
     * Executes this Commissioning console operation and reports the resulting deterministic command status.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $entities = [
            'App\\Commissioning\\Entity\\CommissionPlanEntity',
            'App\\Commissioning\\Entity\\CommissionRateEntity',
            'App\\Commissioning\\Entity\\CommissionRuleEntity',
            'App\\Commissioning\\Entity\\CommissionTierEntity',
            'App\\Commissioning\\Entity\\CommissionBeneficiaryEntity',
            'App\\Commissioning\\Entity\\CommissionAttributionEntity',
            'App\\Commissioning\\Entity\\CommissionCalculationEntity',
            'App\\Commissioning\\Entity\\CommissionCalculationLineEntity',
            'App\\Commissioning\\Entity\\CommissionLedgerEntryEntity',
            'App\\Commissioning\\Entity\\CommissionSettlementBatchEntity',
            'App\\Commissioning\\Entity\\CommissionSettlementBatchEntryEntity',
        ];

        $rows = [];
        $failed = false;

        foreach ($entities as $entity) {
            try {
                $rows[] = [$entity, $this->metadataRepository->getTableName($entity), 'mapped'];
            } catch (\Throwable $exception) {
                $rows[] = [$entity, '-', 'missing: '.$exception->getMessage()];
                $failed = true;
            }
        }

        $io->table(['Entity', 'Table', 'Status'], $rows);

        return $failed ? Command::FAILURE : Command::SUCCESS;
    }
}
