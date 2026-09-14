<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionTierEntity;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionTierEntity>
 */
final class CommissionTierRepository extends ServiceEntityRepository implements CommissionTierRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionTierEntity::class);
    }

    public function save(CommissionTierEntity $tier): void
    {
        $this->getEntityManager()->persist($tier);
        $this->getEntityManager()->flush();
    }

    /**
     * @return list<CommissionTierEntity>
     */
    public function findByRate(CommissionRateEntity $rate): array
    {
        return array_values($this->findBy(['rate' => $rate], ['minimumMinorAmount' => 'ASC']));
    }
}
