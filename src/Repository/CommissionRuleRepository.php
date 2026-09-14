<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionRuleEntity;
use App\Commissioning\RepositoryInterface\CommissionRuleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionRuleEntity>
 */
final class CommissionRuleRepository extends ServiceEntityRepository implements CommissionRuleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionRuleEntity::class);
    }

    public function save(CommissionRuleEntity $rule): void
    {
        $this->getEntityManager()->persist($rule);
        $this->getEntityManager()->flush();
    }

    /**
     * @return list<CommissionRuleEntity>
     */
    public function findActiveByPlanCodeOrdered(string $planCode): array
    {
        return array_values($this->createQueryBuilder('rule')
            ->join('rule.plan', 'plan')
            ->andWhere('plan.code = :planCode')
            ->andWhere('plan.active = true')
            ->andWhere('rule.active = true')
            ->setParameter('planCode', $planCode)
            ->orderBy('rule.priority', 'ASC')
            ->getQuery()
            ->getResult());
    }
}
