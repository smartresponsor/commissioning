<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\Repository\CommissionRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRateRepository::class)]
#[ORM\Table(name: 'commission_rate')]
#[ORM\Index(columns: ['type'], name: 'commission_rate_type_idx')]
class CommissionRateEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionPlanEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionPlanEntity $plan;

    #[ORM\Column(enumType: CommissionRateTypeEnum::class)]
    private CommissionRateTypeEnum $type;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $percentageRate;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $fixedMinorAmount;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $currencyCode;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    public function __construct(
        CommissionPlanEntity $plan,
        CommissionRateTypeEnum $type,
        ?string $percentageRate,
        ?int $fixedMinorAmount,
        ?string $currencyCode,
    ) {
        $this->plan = $plan;
        $this->type = $type;
        $this->percentageRate = $percentageRate;
        $this->fixedMinorAmount = $fixedMinorAmount;
        $this->currencyCode = $currencyCode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): CommissionPlanEntity
    {
        return $this->plan;
    }

    public function getType(): CommissionRateTypeEnum
    {
        return $this->type;
    }

    public function getPercentageRate(): ?string
    {
        return $this->percentageRate;
    }

    public function getFixedMinorAmount(): ?int
    {
        return $this->fixedMinorAmount;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
