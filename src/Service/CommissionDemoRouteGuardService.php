<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Coordinates Commissioning application behavior implemented by CommissionDemoRouteGuardService across typed collaborators and boundaries.
 */
final class CommissionDemoRouteGuardService implements CommissionDemoRouteGuardServiceInterface
{
    public function __construct(private readonly string $environment)
    {
    }

    /**
     * Asserts the Commissioning application invariant represented by this contract and fails explicitly otherwise.
     */
    public function assertDemoRouteAllowed(): void
    {
        if ('dev' === $this->environment || 'test' === $this->environment) {
            return;
        }

        throw new NotFoundHttpException('Commissioning demo route is not available in this environment.');
    }
}
