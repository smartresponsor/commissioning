<?php

declare(strict_types=1);

namespace App\Commissioning\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'commissioning:routes:audit',
    description: 'Lists Commissioning routes registered in the Symfony router.',
)]
final class CommissionRouteAuditCommand extends Command
{
    public function __construct(private readonly RouterInterface $router)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $rows = [];

        foreach ($this->router->getRouteCollection() as $nameEntity => $route) {
            if (!str_starts_with((string) $nameEntity, 'commissioning_')) {
                continue;
            }

            $rows[] = [
                $nameEntity,
                $route->getPath(),
                implode('|', $route->getMethods()),
            ];
        }

        $io->table(['Name', 'Path', 'Methods'], $rows);

        return Command::SUCCESS;
    }
}
