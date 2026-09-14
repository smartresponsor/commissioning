<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Enum\CommissionLedgerStatusEnum;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionLedgerEntryEntity>
 */
final class CommissionLedgerEntryRepository extends ServiceEntityRepository implements CommissionLedgerEntryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionLedgerEntryEntity::class);
    }

    public function save(CommissionLedgerEntryEntity $entry): void
    {
        $this->getEntityManager()->persist($entry);
        $this->getEntityManager()->flush();
    }

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findPendingByBeneficiaryReference(string $beneficiaryReference): array
    {
        return array_values($this->findBy([
            'beneficiaryReference' => $beneficiaryReference,
            'status' => CommissionLedgerStatusEnum::Pending,
        ]));
    }

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findSettlementReady(): array
    {
        return array_values($this->findBy(['status' => CommissionLedgerStatusEnum::SettlementReady]));
    }
}
