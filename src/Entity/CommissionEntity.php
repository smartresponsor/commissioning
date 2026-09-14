<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\EntityInterface\CommissionEntityInterface;
use App\Commissioning\Repository\CommissionRepository;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRepository::class)]
#[ORM\Table(name: 'commission')]
#[ORM\Index(columns: ['vendor_reference'], name: 'commission_vendor_reference_idx')]
#[ORM\Index(columns: ['product_reference'], name: 'commission_product_reference_idx')]
#[ORM\Index(columns: ['order_reference'], name: 'commission_order_reference_idx')]
#[ORM\Index(columns: ['effective_from'], name: 'commission_effective_from_idx')]
#[ORM\UniqueConstraint(name: 'commission_vendor_product_effective_unique', columns: ['vendor_reference', 'product_reference', 'effective_from'])]
class CommissionEntity implements CommissionEntityInterface
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionPlanEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private CommissionPlanEntity $plan;

    #[ORM\ManyToOne(targetEntity: CommissionTypeEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private CommissionTypeEntity $type;

    #[ORM\ManyToOne(targetEntity: CommissionDirectionEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private CommissionDirectionEntity $direction;

    #[ORM\Column(length: 120)]
    private string $vendorReference;

    #[ORM\Column(length: 120)]
    private string $productReference;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $orderReference;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $percentage;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 3)]
    private string $currencyCode;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $effectiveFrom;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $effectiveUntil;

    public function __construct(
        CommissionPlanEntity $plan,
        CommissionTypeEntity $type,
        CommissionDirectionEntity $direction,
        string $vendorReference,
        string $productReference,
        string $percentage,
        string $amount,
        string $currencyCode = 'USD',
        ?string $orderReference = null,
        ?\DateTimeImmutable $effectiveFrom = null,
        ?\DateTimeImmutable $effectiveUntil = null,
    ) {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectState(true, true, 'active');
        $this->plan = $plan;
        $this->type = $type;
        $this->direction = $direction;
        $this->vendorReference = $vendorReference;
        $this->productReference = $productReference;
        $this->orderReference = $orderReference;
        $this->percentage = $percentage;
        $this->amount = $amount;
        $this->currencyCode = strtoupper($currencyCode);
        $this->effectiveFrom = $effectiveFrom ?? new \DateTimeImmutable();
        $this->effectiveUntil = $effectiveUntil;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): CommissionPlanEntity
    {
        return $this->plan;
    }

    public function getType(): CommissionTypeEntity
    {
        return $this->type;
    }

    public function getDirection(): CommissionDirectionEntity
    {
        return $this->direction;
    }

    public function getVendorReference(): string
    {
        return $this->vendorReference;
    }

    public function getProductReference(): string
    {
        return $this->productReference;
    }

    public function getOrderReference(): ?string
    {
        return $this->orderReference;
    }

    public function getPercentage(): string
    {
        return $this->percentage;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getEffectiveFrom(): \DateTimeImmutable
    {
        return $this->effectiveFrom;
    }

    public function getEffectiveUntil(): ?\DateTimeImmutable
    {
        return $this->effectiveUntil;
    }
}
