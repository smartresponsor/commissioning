<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Calculator;

use App\Commissioning\Calculator\CommissionPercentageCalculator;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;
use PHPUnit\Framework\TestCase;

final class CommissionPercentageCalculatorTest extends TestCase
{
    public function testPercentageCalculation(): void
    {
        $calculator = new CommissionPercentageCalculator();

        $result = $calculator->calculate(
            new CommissionBasisValueObject('event-1', new CommissionMoneyValueObject('USD', 10000)),
            new CommissionRateInputDTO(CommissionRateTypeEnum::Percentage->value, '10', null, 'USD'),
        );

        self::assertSame(1000, $result->commissionMinorAmount);
    }
}
