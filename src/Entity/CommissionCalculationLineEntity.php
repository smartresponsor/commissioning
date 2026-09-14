<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\Repository\CommissionCalculationLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionCalculationLineRepository::class)]
#[ORM\Table(name: 'commission_calculation_line')]
class CommissionCalculationLineEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionCalculationEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionCalculationEntity $calculation;

    #[ORM\Column(enumType: CommissionCalculationLineTypeEnum::class)]
    private CommissionCalculationLineTypeEnum $lineType;

    #[ORM\Column(length: 3)]
    private string $currencyCode;

    #[ORM\Column(type: 'integer')]
    private int $minorAmount;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $explanation;

    public function __construct(
        CommissionCalculationEntity $calculation,
        CommissionCalculationLineTypeEnum $lineType,
        string $currencyCode,
        int $minorAmount,
        ?string $explanation = null,
    ) {
        $this->calculation = $calculation;
        $this->lineType = $lineType;
        $this->currencyCode = $currencyCode;
        $this->minorAmount = $minorAmount;
        $this->explanation = $explanation;
    }

    public function getMinorAmount(): int
    {
        return $this->minorAmount;
    }
}
