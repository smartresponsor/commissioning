<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

interface CommissionTypeEntityInterface
{
    public function getCode(): string;

    public function getOperation(): string;
}
