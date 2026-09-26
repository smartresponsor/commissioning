<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchEntryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionSettlementBatchEntryEntity>
 */
final class CommissionSettlementBatchEntryRepository extends ServiceEntityRepository implements CommissionSettlementBatchEntryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionSettlementBatchEntryEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionSettlementBatchEntryEntity $entry): void
    {
        $this->getEntityManager()->persist($entry);
        $this->getEntityManager()->flush();
    }

    /**
     * Performs the existsForBatchAndLedgerEntry operation defined by this typed Commissioning application contract.
     */
    public function existsForBatchAndLedgerEntry(
        CommissionSettlementBatchEntity $batch,
        CommissionLedgerEntryEntity $ledgerEntry,
    ): bool {
        return null !== $this->findOneBy([
            'batch' => $batch,
            'ledgerEntry' => $ledgerEntry,
        ]);
    }

    /**
     * @return list<CommissionSettlementBatchEntryEntity>
     */
    public function findByBatchReference(string $batchReference): array
    {
        return array_values($this->createQueryBuilder('entry')
            ->join('entry.batch', 'batch')
            ->andWhere('batch.batchReference = :batchReference')
            ->setParameter('batchReference', $batchReference)
            ->getQuery()
            ->getResult());
    }
}
