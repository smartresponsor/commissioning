<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionCalculationStatusEnum;
use App\Commissioning\Repository\CommissionCalculationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionCalculationRepository::class)]
#[ORM\Table(name: 'commission_calculation')]
#[ORM\Index(columns: ['economic_event_reference'], name: 'commission_calculation_event_idx')]
class CommissionCalculationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionPlanEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionPlanEntity $plan;

    #[ORM\Column(length: 120)]
    private string $economicEventReference;

    #[ORM\Column(length: 3)]
    private string $currencyCode;

    #[ORM\Column(type: 'integer')]
    private int $basisMinorAmount;

    #[ORM\Column(type: 'integer')]
    private int $commissionMinorAmount;

    #[ORM\Column(enumType: CommissionCalculationStatusEnum::class)]
    private CommissionCalculationStatusEnum $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $calculatedAt;

    public function __construct(
        CommissionPlanEntity $plan,
        string $economicEventReference,
        string $currencyCode,
        int $basisMinorAmount,
        int $commissionMinorAmount,
    ) {
        $this->plan = $plan;
        $this->economicEventReference = $economicEventReference;
        $this->currencyCode = $currencyCode;
        $this->basisMinorAmount = $basisMinorAmount;
        $this->commissionMinorAmount = $commissionMinorAmount;
        $this->status = CommissionCalculationStatusEnum::Calculated;
        $this->calculatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEconomicEventReference(): string
    {
        return $this->economicEventReference;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getBasisMinorAmount(): int
    {
        return $this->basisMinorAmount;
    }

    public function getCommissionMinorAmount(): int
    {
        return $this->commissionMinorAmount;
    }

    public function getStatus(): CommissionCalculationStatusEnum
    {
        return $this->status;
    }
}
