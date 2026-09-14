<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Repository\CommissionPlanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionPlanRepository::class)]
#[ORM\Table(name: 'commission_plan')]
#[ORM\UniqueConstraint(name: 'commission_plan_code_unique', columns: ['code'])]
class CommissionPlanEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private string $code;

    #[ORM\Column(length: 180)]
    private string $nameEntity;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $code, string $nameEntity)
    {
        $this->code = $code;
        $this->nameEntity = $nameEntity;
        $this->createdAt = new \DateTimeImmutable();
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }
}
