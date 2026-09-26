<?php

declare(strict_types=1);

namespace App\Commissioning\Repository;

use App\Commissioning\Entity\CommissionEntity;
use App\Commissioning\RepositoryInterface\CommissionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionEntity>
 */
final class CommissionRepository extends ServiceEntityRepository implements CommissionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionEntity::class);
    }

    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionEntity $commission): void
    {
        $this->getEntityManager()->persist($commission);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findActiveForVendorProduct(string $vendorReference, string $productReference): ?CommissionEntity
    {
        return $this->findOneBy([
            'vendorReference' => $vendorReference,
            'productReference' => $productReference,
        ]);
    }
}
