<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionAttributionSourceTypeEnum;
use App\Commissioning\Repository\CommissionAttributionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents persisted Commissioning state for CommissionAttributionEntity records and their application lifecycle.
 */
#[ORM\Entity(repositoryClass: CommissionAttributionRepository::class)]
#[ORM\Table(name: 'commission_attribution')]
#[ORM\Index(columns: ['economic_event_reference'], name: 'commission_attribution_event_idx')]
class CommissionAttributionEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(enumType: CommissionAttributionSourceTypeEnum::class)]
    private CommissionAttributionSourceTypeEnum $sourceType;

    #[ORM\Column(length: 120)]
    private string $sourceReference;

    #[ORM\Column(length: 120)]
    private string $economicEventReference;

    public function __construct(
        CommissionAttributionSourceTypeEnum $sourceType,
        string $sourceReference,
        string $economicEventReference,
    ) {
        $this->sourceType = $sourceType;
        $this->sourceReference = $sourceReference;
        $this->economicEventReference = $economicEventReference;
    }
}
