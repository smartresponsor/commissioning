<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionPlanEntity>
 */
final class CommissionPlanRepository extends ServiceEntityRepository implements CommissionPlanRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionPlanEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionPlanEntity $plan): void
    {
        $this->getEntityManager()->persist($plan);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByCode(string $code): ?CommissionPlanEntity
    {
        return $this->findOneBy(['code' => $code]);
    }
}
