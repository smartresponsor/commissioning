<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Repository\CommissionSettlementBatchEntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents persisted Commissioning state for CommissionSettlementBatchEntryEntity records and their application lifecycle.
 */
#[ORM\Entity(repositoryClass: CommissionSettlementBatchEntryRepository::class)]
#[ORM\Table(name: 'commission_settlement_batch_entry')]
#[ORM\UniqueConstraint(name: 'commission_settlement_batch_entry_unique', columns: ['batch_id', 'ledger_entry_id'])]
class CommissionSettlementBatchEntryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionSettlementBatchEntity::class)]
    #[ORM\JoinColumn(name: 'batch_id', nullable: false)]
    private CommissionSettlementBatchEntity $batch;

    #[ORM\ManyToOne(targetEntity: CommissionLedgerEntryEntity::class)]
    #[ORM\JoinColumn(name: 'ledger_entry_id', nullable: false)]
    private CommissionLedgerEntryEntity $ledgerEntry;

    public function __construct(
        CommissionSettlementBatchEntity $batch,
        CommissionLedgerEntryEntity $ledgerEntry,
    ) {
        $this->batch = $batch;
        $this->ledgerEntry = $ledgerEntry;
    }

    public function getBatch(): CommissionSettlementBatchEntity
    {
        return $this->batch;
    }

    public function getLedgerEntry(): CommissionLedgerEntryEntity
    {
        return $this->ledgerEntry;
    }
}
