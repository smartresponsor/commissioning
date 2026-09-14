<?php

declare(strict_types=1);

namespace App\Commissioning\Command;

use App\Commissioning\ServiceInterface\CommissionDevelopmentSeedServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'commissioning:dev:seed',
    description: 'Seeds default Commissioning development data.',
)]
final class CommissionDevelopmentSeedCommand extends Command
{
    public function __construct(private readonly CommissionDevelopmentSeedServiceInterface $seedService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $result = $this->seedService->seedDefault();

        $io->success('Commissioning development seed completed.');
        $io->table(['Metric', 'Count'], [
            ['Created plans', $result['createdPlans']],
            ['Created rates', $result['createdRates']],
            ['Created rules', $result['createdRules']],
            ['Created tiers', $result['createdTiers']],
        ]);

        return Command::SUCCESS;
    }
}
