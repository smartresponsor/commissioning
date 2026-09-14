<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

interface CommissionDirectionEntityInterface
{
    public function getCode(): string;

    public function targetsShipment(): bool;

    public function targetsPayment(): bool;

    public function targetsPrice(): bool;

    public function targetsStorage(): bool;

    public function targetsOrderTotal(): bool;

    public function targetsProductCategory(): bool;
}
