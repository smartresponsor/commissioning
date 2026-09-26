<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\EntityInterface\CommissionTypeEntityInterface;
use App\Commissioning\Repository\CommissionTypeRepository;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents persisted Commissioning state for CommissionTypeEntity records and their application lifecycle.
 */
#[ORM\Entity(repositoryClass: CommissionTypeRepository::class)]
#[ORM\Table(name: 'commission_type')]
#[ORM\UniqueConstraint(name: 'commission_type_code_unique', columns: ['code'])]
class CommissionTypeEntity implements CommissionTypeEntityInterface
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

    #[ORM\Column(length: 64)]
    private string $operation;

    public function __construct(string $code, string $nameEntity, string $operation)
    {
        $this->initializeObjectIdentity(null, $code);
        $this->initializeObjectAudit();
        $this->initializeObjectState(true, true, 'active');
        $this->code = $code;
        $this->nameEntity = $nameEntity;
        $this->operation = $operation;
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

    public function getOperation(): string
    {
        return $this->operation;
    }
}
