<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionIdempotencyResultDTO
{
    public function __construct(
        public bool $duplicate,
        public string $reference,
    ) {
    }
}
