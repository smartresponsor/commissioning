<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRuntimeCheckResultDTO
{
    /**
     * @param list<string> $messages
     */
    public function __construct(
        public string $nameEntity,
        public string $status,
        public array $messages = [],
    ) {
    }
}
