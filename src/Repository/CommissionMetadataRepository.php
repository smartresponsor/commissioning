<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\RepositoryInterface\CommissionMetadataRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Persists and queries Commissioning records through the CommissionMetadataRepository Doctrine repository boundary.
 */
final class CommissionMetadataRepository implements CommissionMetadataRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getTableName(string $entityClass): string
    {
        return $this->entityManager->getClassMetadata($entityClass)->getTableName();
    }
}
