<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionLedgerStatusEnum;
use App\Commissioning\Repository\CommissionLedgerEntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents persisted Commissioning state for CommissionLedgerEntryEntity records and their application lifecycle.
 */
#[ORM\Entity(repositoryClass: CommissionLedgerEntryRepository::class)]
#[ORM\Table(name: 'commission_ledger_entry')]
#[ORM\Index(columns: ['beneficiary_reference'], name: 'commission_ledger_beneficiary_idx')]
#[ORM\Index(columns: ['status'], name: 'commission_ledger_status_idx')]
class CommissionLedgerEntryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionCalculationEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionCalculationEntity $calculation;

    #[ORM\Column(length: 120)]
    private string $beneficiaryReference;

    #[ORM\Column(length: 3)]
    private string $currencyCode;

    #[ORM\Column(type: 'integer')]
    private int $minorAmount;

    #[ORM\Column(enumType: CommissionLedgerStatusEnum::class)]
    private CommissionLedgerStatusEnum $status;

    public function __construct(
        CommissionCalculationEntity $calculation,
        string $beneficiaryReference,
        string $currencyCode,
        int $minorAmount,
    ) {
        $this->calculation = $calculation;
        $this->beneficiaryReference = $beneficiaryReference;
        $this->currencyCode = $currencyCode;
        $this->minorAmount = $minorAmount;
        $this->status = CommissionLedgerStatusEnum::Pending;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalculation(): CommissionCalculationEntity
    {
        return $this->calculation;
    }

    public function getBeneficiaryReference(): string
    {
        return $this->beneficiaryReference;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getMinorAmount(): int
    {
        return $this->minorAmount;
    }

    public function getStatus(): CommissionLedgerStatusEnum
    {
        return $this->status;
    }

    /**
     * Applies the requested Commissioning lifecycle transition and returns the resulting application state.
     */
    public function markSettlementReady(): void
    {
        $this->status = CommissionLedgerStatusEnum::SettlementReady;
    }

    /**
     * Applies the requested Commissioning lifecycle transition and returns the resulting application state.
     */
    public function markSettled(): void
    {
        $this->status = CommissionLedgerStatusEnum::Settled;
    }

    /**
     * Performs the reverse operation defined by this typed Commissioning application contract.
     */
    public function reverse(): void
    {
        $this->status = CommissionLedgerStatusEnum::Reversed;
    }
}
