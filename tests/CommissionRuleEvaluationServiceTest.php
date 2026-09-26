<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\Service\CommissionRuleEvaluationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CommissionRuleEvaluationServiceTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, string|null, array<string, scalar|null>, bool}>
     */
    public static function ruleCases(): iterable
    {
        yield 'always ignores missing context' => ['always', 'region', null, [], true];
        yield 'equals matches scalar string representation' => ['equals', 'region', 'US', ['region' => 'US'], true];
        yield 'equals rejects a different value' => ['equals', 'region', 'US', ['region' => 'CA'], false];
        yield 'not equals accepts a different value' => ['not_equals', 'region', 'US', ['region' => 'CA'], true];
        yield 'greater than or equal accepts boundary' => ['greater_than_or_equal', 'amount', '100', ['amount' => 100], true];
        yield 'greater than or equal rejects lower numeric value' => ['greater_than_or_equal', 'amount', '100', ['amount' => 99], false];
        yield 'less than or equal accepts decimal boundary' => ['less_than_or_equal', 'rate', '7.5', ['rate' => 7.5], true];
        yield 'numeric operators reject non numeric values' => ['greater_than_or_equal', 'amount', '100', ['amount' => 'not-a-number'], false];
        yield 'in trims configured choices' => ['in', 'channel', 'direct, partner, reseller', ['channel' => 'partner'], true];
        yield 'in rejects values outside configured choices' => ['in', 'channel', 'direct,partner', ['channel' => 'affiliate'], false];
        yield 'in rejects absent expected values' => ['in', 'channel', null, ['channel' => 'direct'], false];
    }

    /**
     * @param array<string, scalar|null> $context
     */
    #[DataProvider('ruleCases')]
    public function testRuleOperators(
        string $operator,
        string $ruleKey,
        ?string $expectedValue,
        array $context,
        bool $expected,
    ): void {
        self::assertSame(
            $expected,
            (new CommissionRuleEvaluationService())->matches($operator, $ruleKey, $expectedValue, $context),
        );
    }

    public function testUnknownOperatorFailsFast(): void
    {
        $this->expectException(\ValueError::class);

        (new CommissionRuleEvaluationService())->matches(
            'unsupported',
            'region',
            'US',
            ['region' => 'US'],
        );
    }
}
