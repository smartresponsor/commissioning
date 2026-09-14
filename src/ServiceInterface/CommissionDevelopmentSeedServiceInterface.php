<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

interface CommissionDevelopmentSeedServiceInterface
{
    /**
     * Seeds default Commissioning development data.
     *
     * @return array<string, scalar>
     */
    public function seedDefault(): array;
}
