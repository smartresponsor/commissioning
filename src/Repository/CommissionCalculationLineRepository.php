<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\RepositoryInterface\CommissionCalculationLineRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionCalculationLineEntity>
 */
final class CommissionCalculationLineRepository extends ServiceEntityRepository implements CommissionCalculationLineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionCalculationLineEntity::class);
    }

    public function save(CommissionCalculationLineEntity $line): void
    {
        $this->getEntityManager()->persist($line);
        $this->getEntityManager()->flush();
    }
}
