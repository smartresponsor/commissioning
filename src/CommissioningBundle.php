<?php

declare(strict_types=1);

namespace App\Commissioning;

use App\Commissioning\DependencyInjection\CommissioningExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Defines the Commissioning application responsibility represented by CommissioningBundle within its canonical typed layer.
 */
final class CommissioningBundle extends Bundle
{
    public function getContainerExtension(): CommissioningExtension
    {
        return new CommissioningExtension();
    }
}
