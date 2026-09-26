<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionDevelopmentSeedServiceInterface to typed application collaborators.
 */
interface CommissionDevelopmentSeedServiceInterface
{
    /**
     * Seeds default Commissioning development data.
     *
     * @return array<string, scalar>
     */
    public function seedDefault(): array;
}
