<?php

declare(strict_types=1);

namespace App\Commissioning;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Defines the Commissioning application responsibility represented by Kernel within its canonical typed layer.
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
