<?php

declare(strict_types=1);

namespace App\Commissioning\Exception;

/**
 * Defines the Commissioning application responsibility represented by CommissionApiRequestMappingException within its canonical typed layer.
 */
final class CommissionApiRequestMappingException extends \InvalidArgumentException
{
    /**
     * @param array<int, string> $violations
     */
    public function __construct(private readonly array $violations)
    {
        parent::__construct('Invalid Commissioning API request.');
    }

    /**
     * @return array<int, string>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
