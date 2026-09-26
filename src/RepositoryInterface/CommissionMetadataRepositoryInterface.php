<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

/**
 * Defines the Commissioning persistence contract exposed by CommissionMetadataRepositoryInterface to application services and resolvers.
 */
interface CommissionMetadataRepositoryInterface
{
    /**
     * @param class-string $entityClass
     */
    public function getTableName(string $entityClass): string;
}
