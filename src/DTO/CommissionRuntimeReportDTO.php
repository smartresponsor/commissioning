<?php

declare(strict_types=1);

namespace App\Commissioning\DTO;

final readonly class CommissionRuntimeReportDTO
{
    /**
     * @param list<CommissionRuntimeCheckResultDTO> $checks
     */
    public function __construct(
        public string $component,
        public string $status,
        public array $checks,
    ) {
    }
}
