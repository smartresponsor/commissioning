<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionTypeEntity;
use App\Commissioning\RepositoryInterface\CommissionTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionTypeEntity>
 */
final class CommissionTypeRepository extends ServiceEntityRepository implements CommissionTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionTypeEntity::class);
    }

    public function save(CommissionTypeEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function findOneByCode(string $code): ?CommissionTypeEntity
    {
        return $this->findOneBy(['code' => $code]);
    }
}
