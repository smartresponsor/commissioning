<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionRateEntity>
 */
final class CommissionRateRepository extends ServiceEntityRepository implements CommissionRateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionRateEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionRateEntity $rate): void
    {
        $this->getEntityManager()->persist($rate);
        $this->getEntityManager()->flush();
    }

    /**
     * @return list<CommissionRateEntity>
     */
    public function findActiveByPlanCode(string $planCode, ?string $currencyCode = null): array
    {
        $builder = $this->createQueryBuilder('rate')
            ->join('rate.plan', 'plan')
            ->andWhere('plan.code = :planCode')
            ->andWhere('plan.active = true')
            ->andWhere('rate.active = true')
            ->setParameter('planCode', $planCode);

        if (null !== $currencyCode) {
            $builder
                ->andWhere('rate.currencyCode = :currencyCode OR rate.currencyCode IS NULL')
                ->setParameter('currencyCode', $currencyCode);
        }

        return array_values($builder
            ->orderBy('rate.currencyCode', 'DESC')
            ->getQuery()
            ->getResult());
    }
}
