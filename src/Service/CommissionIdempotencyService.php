<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionIdempotencyResultDTO;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionIdempotencyServiceInterface;

final class CommissionIdempotencyService implements CommissionIdempotencyServiceInterface
{
    public function __construct(private readonly CommissionCalculationRepositoryInterface $calculationRepository)
    {
    }

    public function checkEconomicEvent(string $economicEventReference): CommissionIdempotencyResultDTO
    {
        return new CommissionIdempotencyResultDTO(
            duplicate: null !== $this->calculationRepository->findOneByEconomicEventReference($economicEventReference),
            reference: $economicEventReference,
        );
    }
}
