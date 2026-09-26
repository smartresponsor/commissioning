<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionDirectionEntity;
use App\Commissioning\RepositoryInterface\CommissionDirectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionDirectionEntity>
 */
final class CommissionDirectionRepository extends ServiceEntityRepository implements CommissionDirectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionDirectionEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionDirectionEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByCode(string $code): ?CommissionDirectionEntity
    {
        return $this->findOneBy(['code' => $code]);
    }
}
