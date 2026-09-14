<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;
use App\Commissioning\Repository\CommissionBeneficiaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionBeneficiaryRepository::class)]
#[ORM\Table(name: 'commission_beneficiary')]
#[ORM\UniqueConstraint(name: 'commission_beneficiary_reference_unique', columns: ['beneficiary_type', 'beneficiary_reference'])]
class CommissionBeneficiaryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(enumType: CommissionBeneficiaryTypeEnum::class)]
    private CommissionBeneficiaryTypeEnum $beneficiaryType;

    #[ORM\Column(length: 120)]
    private string $beneficiaryReference;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $displayName;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    public function __construct(
        CommissionBeneficiaryTypeEnum $beneficiaryType,
        string $beneficiaryReference,
        ?string $displayName = null,
    ) {
        $this->beneficiaryType = $beneficiaryType;
        $this->beneficiaryReference = $beneficiaryReference;
        $this->displayName = $displayName;
    }

    public function getBeneficiaryReference(): string
    {
        return $this->beneficiaryReference;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
