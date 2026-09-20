<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Calculator;

use App\Commissioning\Calculator\CommissionCalculationEngine;
use App\Commissioning\Calculator\CommissionFixedCalculator;
use App\Commissioning\Calculator\CommissionHybridCalculator;
use App\Commissioning\Calculator\CommissionPercentageCalculator;
use App\Commissioning\Calculator\CommissionTieredCalculator;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    public function testCalculationEngineRoutesSupportedRateType(): void
    {
        $engine = new CommissionCalculationEngine([
            new CommissionFixedCalculator(),
            new CommissionPercentageCalculator(),
        ]);

        $result = $engine->calculate(
            $this->basis(12_345),
            new CommissionRateInputDTO(
                rateType: CommissionRateTypeEnum::Percentage->value,
                percentageRate: '12.5',
                currencyCode: 'USD',
            ),
        );

        self::assertSame(1_543, $result->commissionMinorAmount);
        self::assertSame(12_345, $result->basisMinorAmount);
        self::assertSame('USD', $result->currencyCode);
        self::assertSame(CommissionCalculationLineTypeEnum::PercentageCommission->value, $result->lines[0]->lineType);
    }

    public function testCalculationEngineRejectsUnsupportedRateType(): void
    {
        $engine = new CommissionCalculationEngine([
            new CommissionFixedCalculator(),
            new CommissionPercentageCalculator(),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported commission rate type "unsupported".');

        $engine->calculate(
            $this->basis(10_000),
            new CommissionRateInputDTO(rateType: 'unsupported'),
        );
    }

    public function testFixedCalculatorUsesConfiguredMinorAmount(): void
    {
        $calculator = new CommissionFixedCalculator();

        self::assertTrue($calculator->supports(CommissionRateTypeEnum::Fixed->value));
        self::assertFalse($calculator->supports(CommissionRateTypeEnum::Hybrid->value));

        $result = $calculator->calculate(
            $this->basis(50_000),
            new CommissionRateInputDTO(
                rateType: CommissionRateTypeEnum::Fixed->value,
                fixedMinorAmount: 775,
                currencyCode: 'USD',
            ),
        );

        self::assertSame(775, $result->commissionMinorAmount);
        self::assertSame(775, $result->lines[0]->minorAmount);
        self::assertSame(CommissionCalculationLineTypeEnum::FixedCommission->value, $result->lines[0]->lineType);
    }

    public function testHybridCalculatorAddsPercentageAndFixedParts(): void
    {
        $calculator = new CommissionHybridCalculator();

        self::assertTrue($calculator->supports(CommissionRateTypeEnum::Hybrid->value));
        self::assertFalse($calculator->supports(CommissionRateTypeEnum::Tiered->value));

        $result = $calculator->calculate(
            $this->basis(20_000),
            new CommissionRateInputDTO(
                rateType: CommissionRateTypeEnum::Hybrid->value,
                percentageRate: '7.5',
                fixedMinorAmount: 250,
                currencyCode: 'USD',
            ),
        );

        self::assertSame(1_750, $result->commissionMinorAmount);
        self::assertCount(2, $result->lines);
        self::assertSame(1_500, $result->lines[0]->minorAmount);
        self::assertSame(250, $result->lines[1]->minorAmount);
    }

    public function testTieredCalculatorHonorsInclusiveBoundaries(): void
    {
        $calculator = new CommissionTieredCalculator();
        $rate = new CommissionRateInputDTO(
            rateType: CommissionRateTypeEnum::Tiered->value,
            currencyCode: 'USD',
            tiers: [
                [
                    'minimumMinorAmount' => 0,
                    'maximumMinorAmount' => 99_999,
                    'percentageRate' => '5',
                ],
                [
                    'minimumMinorAmount' => 100_000,
                    'maximumMinorAmount' => null,
                    'percentageRate' => '8',
                ],
            ],
        );

        $lower = $calculator->calculate($this->basis(99_999), $rate);
        $upper = $calculator->calculate($this->basis(100_000), $rate);

        self::assertSame(5_000, $lower->commissionMinorAmount);
        self::assertSame(8_000, $upper->commissionMinorAmount);
        self::assertSame(CommissionCalculationLineTypeEnum::TierAdjustment->value, $upper->lines[0]->lineType);
    }

    public function testCalculatorsDefaultMissingOptionalRatePartsToZero(): void
    {
        $fixed = (new CommissionFixedCalculator())->calculate(
            $this->basis(10_000),
            new CommissionRateInputDTO(rateType: CommissionRateTypeEnum::Fixed->value),
        );
        $hybrid = (new CommissionHybridCalculator())->calculate(
            $this->basis(10_000),
            new CommissionRateInputDTO(rateType: CommissionRateTypeEnum::Hybrid->value),
        );

        self::assertSame(0, $fixed->commissionMinorAmount);
        self::assertSame(0, $hybrid->commissionMinorAmount);
    }

    private function basis(int $minorAmount): CommissionBasisValueObject
    {
        return new CommissionBasisValueObject(
            economicEventReference: 'event-test',
            money: new CommissionMoneyValueObject('USD', $minorAmount),
        );
    }
}
