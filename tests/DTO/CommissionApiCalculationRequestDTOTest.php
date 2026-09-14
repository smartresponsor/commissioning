<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\DTO;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use PHPUnit\Framework\TestCase;

final class CommissionApiCalculationRequestDTOTest extends TestCase
{
    public function testApiCalculationRequestCarriesScalarReferences(): void
    {
        $dto = new CommissionApiCalculationRequestDTO(
            eventReference: 'event-1',
            currencyCode: 'USD',
            basisMinorAmount: 10000,
            planCode: 'default',
            attributionSourceType: 'partner',
            attributionSourceReference: 'partner-1',
            context: ['commission_rate_type' => 'percentage'],
        );

        self::assertSame('event-1', $dto->eventReference);
        self::assertSame('partner-1', $dto->attributionSourceReference);
        self::assertSame('percentage', $dto->context['commission_rate_type']);
    }
}
