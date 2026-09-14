<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

use App\Commissioning\DTO\CommissionRuntimeReportDTO;

interface CommissionRuntimeAuditServiceInterface
{
    public function audit(): CommissionRuntimeReportDTO;
}
