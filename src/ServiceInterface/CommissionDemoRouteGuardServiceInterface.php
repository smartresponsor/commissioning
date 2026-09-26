<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionDemoRouteGuardServiceInterface to typed application collaborators.
 */
interface CommissionDemoRouteGuardServiceInterface
{
    /**
     * Asserts the Commissioning application invariant represented by this contract and fails explicitly otherwise.
     */
    public function assertDemoRouteAllowed(): void;
}
