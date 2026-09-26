<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionCalculationEntity>
 */
final class CommissionCalculationRepository extends ServiceEntityRepository implements CommissionCalculationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionCalculationEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionCalculationEntity $calculation): void
    {
        $this->getEntityManager()->persist($calculation);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByEconomicEventReference(string $economicEventReference): ?CommissionCalculationEntity
    {
        return $this->findOneBy(['economicEventReference' => $economicEventReference]);
    }
}
