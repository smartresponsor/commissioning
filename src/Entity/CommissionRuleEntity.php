<?php

declare(strict_types=1);

namespace App\Commissioning\Entity;

use App\Commissioning\Enum\CommissionRuleOperatorEnum;
use App\Commissioning\Repository\CommissionRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRuleRepository::class)]
#[ORM\Table(name: 'commission_rule')]
#[ORM\Index(columns: ['rule_key'], name: 'commission_rule_key_idx')]
class CommissionRuleEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CommissionPlanEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CommissionPlanEntity $plan;

    #[ORM\Column(length: 120)]
    private string $ruleKey;

    #[ORM\Column(enumType: CommissionRuleOperatorEnum::class)]
    private CommissionRuleOperatorEnum $operator;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expectedValue;

    #[ORM\Column(type: 'integer')]
    private int $priority;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    public function __construct(
        CommissionPlanEntity $plan,
        string $ruleKey,
        CommissionRuleOperatorEnum $operator,
        ?string $expectedValue = null,
        int $priority = 100,
    ) {
        $this->plan = $plan;
        $this->ruleKey = $ruleKey;
        $this->operator = $operator;
        $this->expectedValue = $expectedValue;
        $this->priority = $priority;
    }

    public function getRuleKey(): string
    {
        return $this->ruleKey;
    }

    public function getOperator(): CommissionRuleOperatorEnum
    {
        return $this->operator;
    }

    public function getExpectedValue(): ?string
    {
        return $this->expectedValue;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
