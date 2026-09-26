<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionIdempotencyResultDTO;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionIdempotencyServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionIdempotencyService across typed collaborators and boundaries.
 */
final class CommissionIdempotencyService implements CommissionIdempotencyServiceInterface
{
    public function __construct(private readonly CommissionCalculationRepositoryInterface $calculationRepository)
    {
    }

    /**
     * Checks the supplied Commissioning state against this explicit application readiness contract.
     */
    public function checkEconomicEvent(string $economicEventReference): CommissionIdempotencyResultDTO
    {
        return new CommissionIdempotencyResultDTO(
            duplicate: null !== $this->calculationRepository->findOneByEconomicEventReference($economicEventReference),
            reference: $economicEventReference,
        );
    }
}
