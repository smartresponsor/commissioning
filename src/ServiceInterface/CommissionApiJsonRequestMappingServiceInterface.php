<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use Symfony\Component\HttpFoundation\Request;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionApiJsonRequestMappingServiceInterface to typed application collaborators.
 */
interface CommissionApiJsonRequestMappingServiceInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $dtoClass
     *
     * @return T
     */
    public function map(Request $request, string $dtoClass): object;
}
