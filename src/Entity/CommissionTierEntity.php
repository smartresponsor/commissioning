<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Repository\CommissionTierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents persisted Commissioning state for CommissionTierEntity records and their application lifecycle.
 */
#[ORM\Entity(repositoryClass: CommissionTierRepository::class)]
#[ORM\Table(name: 'commission_tier')]
#[ORM\Index(columns: ['minimum_minor_amount'], name: 'commission_tier_minimum_idx')]
class CommissionTierEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionRateEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionRateEntity $rate;

    #[ORM\Column(type: 'integer')]
    private int $minimumMinorAmount;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maximumMinorAmount;

    #[ORM\Column(type: 'string', length: 32)]
    private string $percentageRate;

    public function __construct(
        CommissionRateEntity $rate,
        int $minimumMinorAmount,
        ?int $maximumMinorAmount,
        string $percentageRate,
    ) {
        $this->rate = $rate;
        $this->minimumMinorAmount = $minimumMinorAmount;
        $this->maximumMinorAmount = $maximumMinorAmount;
        $this->percentageRate = $percentageRate;
    }

    public function getMinimumMinorAmount(): int
    {
        return $this->minimumMinorAmount;
    }

    public function getMaximumMinorAmount(): ?int
    {
        return $this->maximumMinorAmount;
    }

    /**
     * Evaluates whether the supplied Commissioning context satisfies the configured rule contract.
     */
    public function matches(int $basisMinorAmount): bool
    {
        if ($basisMinorAmount < $this->minimumMinorAmount) {
            return false;
        }

        return null === $this->maximumMinorAmount || $basisMinorAmount <= $this->maximumMinorAmount;
    }

    public function getPercentageRate(): string
    {
        return $this->percentageRate;
    }
}
