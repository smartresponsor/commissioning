<?php

declare(strict_types=1);

namespace App\Commissioning;

use App\Commissioning\DependencyInjection\CommissioningExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CommissioningBundle extends Bundle
{
    public function getContainerExtension(): CommissioningExtension
    {
        return new CommissioningExtension();
    }
}
