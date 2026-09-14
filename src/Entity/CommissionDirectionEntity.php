<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\EntityInterface\CommissionDirectionEntityInterface;
use App\Commissioning\Repository\CommissionDirectionRepository;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionDirectionRepository::class)]
#[ORM\Table(name: 'commission_direction')]
#[ORM\UniqueConstraint(name: 'commission_direction_code_unique', columns: ['code'])]
class CommissionDirectionEntity implements CommissionDirectionEntityInterface
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private string $code;

    #[ORM\Column(length: 180)]
    private string $nameEntity;

    #[ORM\Column(type: 'boolean')]
    private bool $toShipment;

    #[ORM\Column(type: 'boolean')]
    private bool $toPayment;

    #[ORM\Column(type: 'boolean')]
    private bool $toPrice;

    #[ORM\Column(type: 'boolean')]
    private bool $toDate;

    #[ORM\Column(type: 'boolean')]
    private bool $toPlatformReward;

    #[ORM\Column(type: 'boolean')]
    private bool $toStorage;

    #[ORM\Column(type: 'boolean')]
    private bool $toProjectType;

    #[ORM\Column(type: 'boolean')]
    private bool $toOrderTotal;

    #[ORM\Column(type: 'boolean')]
    private bool $toProductCategory;

    public function __construct(
        string $code,
        string $nameEntity,
        bool $toShipment = false,
        bool $toPayment = false,
        bool $toPrice = false,
        bool $toDate = false,
        bool $toPlatformReward = false,
        bool $toStorage = false,
        bool $toProjectType = false,
        bool $toOrderTotal = false,
        bool $toProductCategory = false,
    ) {
        $this->initializeObjectIdentity(null, $code);
        $this->initializeObjectAudit();
        $this->initializeObjectState(true, true, 'active');
        $this->code = $code;
        $this->nameEntity = $nameEntity;
        $this->toShipment = $toShipment;
        $this->toPayment = $toPayment;
        $this->toPrice = $toPrice;
        $this->toDate = $toDate;
        $this->toPlatformReward = $toPlatformReward;
        $this->toStorage = $toStorage;
        $this->toProjectType = $toProjectType;
        $this->toOrderTotal = $toOrderTotal;
        $this->toProductCategory = $toProductCategory;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->nameEntity;
    }

    public function targetsShipment(): bool
    {
        return $this->toShipment;
    }

    public function targetsPayment(): bool
    {
        return $this->toPayment;
    }

    public function targetsPrice(): bool
    {
        return $this->toPrice;
    }

    public function targetsDate(): bool
    {
        return $this->toDate;
    }

    public function targetsPlatformReward(): bool
    {
        return $this->toPlatformReward;
    }

    public function targetsStorage(): bool
    {
        return $this->toStorage;
    }

    public function targetsProjectType(): bool
    {
        return $this->toProjectType;
    }

    public function targetsOrderTotal(): bool
    {
        return $this->toOrderTotal;
    }

    public function targetsProductCategory(): bool
    {
        return $this->toProductCategory;
    }
}
