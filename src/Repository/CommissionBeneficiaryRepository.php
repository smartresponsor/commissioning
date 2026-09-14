<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionBeneficiaryEntity;
use App\Commissioning\RepositoryInterface\CommissionBeneficiaryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionBeneficiaryEntity>
 */
final class CommissionBeneficiaryRepository extends ServiceEntityRepository implements CommissionBeneficiaryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionBeneficiaryEntity::class);
    }
}
