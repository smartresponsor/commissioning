<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionRuntimeReportDTO;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionRuntimeAuditServiceInterface to typed application collaborators.
 */
interface CommissionRuntimeAuditServiceInterface
{
    /**
     * Builds the Commissioning runtime audit report from configured routes and persistence metadata.
     */
    public function audit(): CommissionRuntimeReportDTO;
}
