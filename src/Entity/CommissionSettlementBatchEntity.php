<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionSettlementBatchStatusEnum;
use App\Commissioning\Repository\CommissionSettlementBatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionSettlementBatchRepository::class)]
#[ORM\Table(name: 'commission_settlement_batch')]
#[ORM\UniqueConstraint(name: 'commission_settlement_batch_reference_unique', columns: ['batch_reference'])]
class CommissionSettlementBatchEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 120, unique: true)]
    private string $batchReference;

    #[ORM\Column(enumType: CommissionSettlementBatchStatusEnum::class)]
    private CommissionSettlementBatchStatusEnum $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $batchReference)
    {
        $this->batchReference = $batchReference;
        $this->status = CommissionSettlementBatchStatusEnum::Draft;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatchReference(): string
    {
        return $this->batchReference;
    }

    public function getStatus(): CommissionSettlementBatchStatusEnum
    {
        return $this->status;
    }

    public function markExported(): void
    {
        $this->status = CommissionSettlementBatchStatusEnum::Exported;
    }

    public function markAccepted(): void
    {
        $this->status = CommissionSettlementBatchStatusEnum::Accepted;
    }

    public function cancel(): void
    {
        $this->status = CommissionSettlementBatchStatusEnum::Cancelled;
    }
}
