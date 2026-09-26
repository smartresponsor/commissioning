<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionSettlementBatchEntity>
 */
final class CommissionSettlementBatchRepository extends ServiceEntityRepository implements CommissionSettlementBatchRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionSettlementBatchEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionSettlementBatchEntity $batch): void
    {
        $this->getEntityManager()->persist($batch);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByBatchReference(string $batchReference): ?CommissionSettlementBatchEntity
    {
        return $this->findOneBy(['batchReference' => $batchReference]);
    }
}
