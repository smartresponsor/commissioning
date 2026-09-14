<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\ServiceInterface\CommissionDemoRouteGuardServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CommissionDemoRouteGuardService implements CommissionDemoRouteGuardServiceInterface
{
    public function __construct(private readonly string $environment)
    {
    }

    public function assertDemoRouteAllowed(): void
    {
        if ('dev' === $this->environment || 'test' === $this->environment) {
            return;
        }

        throw new NotFoundHttpException('Commissioning demo route is not available in this environment.');
    }
}
