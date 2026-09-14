<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionAttributionEntity;
use App\Commissioning\RepositoryInterface\CommissionAttributionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionAttributionEntity>
 */
final class CommissionAttributionRepository extends ServiceEntityRepository implements CommissionAttributionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionAttributionEntity::class);
    }
}
