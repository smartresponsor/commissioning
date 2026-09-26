<?php

declare(strict_types=1);

namespace App\Commissioning\Enum;

/**
 * Enumerates the canonical Commissioning values represented by CommissionLedgerStatusEnum across typed application boundaries.
 */
enum CommissionLedgerStatusEnum: string
{
    case Pending = 'pending';
    case SettlementReady = 'settlement_ready';
    case Settled = 'settled';
    case Reversed = 'reversed';
}
