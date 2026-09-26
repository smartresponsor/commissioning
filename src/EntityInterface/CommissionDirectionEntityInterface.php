<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

/**
 * Defines the public Commissioning entity contract represented by CommissionDirectionEntityInterface across persistence-aware callers.
 */
interface CommissionDirectionEntityInterface
{
    public function getCode(): string;

    /**
     * Performs the targetsShipment operation defined by this typed Commissioning application contract.
     */
    public function targetsShipment(): bool;

    /**
     * Performs the targetsPayment operation defined by this typed Commissioning application contract.
     */
    public function targetsPayment(): bool;

    /**
     * Performs the targetsPrice operation defined by this typed Commissioning application contract.
     */
    public function targetsPrice(): bool;

    /**
     * Performs the targetsStorage operation defined by this typed Commissioning application contract.
     */
    public function targetsStorage(): bool;

    /**
     * Performs the targetsOrderTotal operation defined by this typed Commissioning application contract.
     */
    public function targetsOrderTotal(): bool;

    /**
     * Performs the targetsProductCategory operation defined by this typed Commissioning application contract.
     */
    public function targetsProductCategory(): bool;
}
