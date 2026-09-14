<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionApiErrorResponseDTO
{
    /**
     * @param array<int, string> $violations
     */
    public function __construct(
        public string $message,
        public array $violations = [],
    ) {
    }
}
