<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

interface CommissionDemoRouteGuardServiceInterface
{
    public function assertDemoRouteAllowed(): void;
}
