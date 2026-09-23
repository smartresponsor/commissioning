<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\Controller\CommissionApiSettlementController;
use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\Exception\CommissionApiRequestMappingException;
use App\Commissioning\Service\CommissionApiJsonRequestMappingService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CommissionApiBoundaryTest extends TestCase
{
    public function testSettlementExportRouteUsesCanonicalBatchReferenceParameter(): void
    {
        $method = new \ReflectionMethod(CommissionApiSettlementController::class, 'exportBatch');
        $attributes = $method->getAttributes(Route::class);

        self::assertCount(1, $attributes);
        self::assertSame(
            '/api/commissioning/settlement/batch/export/{batchReference}',
            $attributes[0]->getArguments()[0],
        );
        self::assertSame('batchReference', $method->getParameters()[0]->getName());
    }

    public function testEmptyJsonBodyIsMappedAsEmptyObjectPayload(): void
    {
        $dto = new CommissionApiCalculationRequestDTO('event-1', 'USD', 0);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with('{}', CommissionApiCalculationRequestDTO::class, 'json')
            ->willReturn($dto);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $result = (new CommissionApiJsonRequestMappingService($serializer, $validator))
            ->map(new Request(content: '   '), CommissionApiCalculationRequestDTO::class);

        self::assertSame($dto, $result);
    }

    public function testDeserializeFailureIsTranslatedToCommissioningMappingException(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects(self::once())
            ->method('deserialize')
            ->willThrowException(new \RuntimeException('Malformed JSON'));

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::never())->method('validate');

        try {
            (new CommissionApiJsonRequestMappingService($serializer, $validator))
                ->map(new Request(content: '{'), CommissionApiCalculationRequestDTO::class);
            self::fail('Expected CommissionApiRequestMappingException.');
        } catch (CommissionApiRequestMappingException $exception) {
            self::assertSame('Invalid Commissioning API request.', $exception->getMessage());
            self::assertSame(
                ['Request body could not be mapped to '.CommissionApiCalculationRequestDTO::class.': Malformed JSON'],
                $exception->getViolations(),
            );
        }
    }

    public function testValidationFailuresExposePropertyQualifiedMessages(): void
    {
        $dto = new CommissionApiCalculationRequestDTO('', 'US', -1);

        $serializer = $this->createStub(SerializerInterface::class);
        $serializer->method('deserialize')->willReturn($dto);

        $violations = new ConstraintViolationList([
            new ConstraintViolation(
                message: 'This value should not be blank.',
                messageTemplate: null,
                parameters: [],
                root: $dto,
                propertyPath: 'eventReference',
                invalidValue: '',
            ),
            new ConstraintViolation(
                message: 'This value should have exactly 3 characters.',
                messageTemplate: null,
                parameters: [],
                root: $dto,
                propertyPath: 'currencyCode',
                invalidValue: 'US',
            ),
        ]);

        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn($violations);

        try {
            (new CommissionApiJsonRequestMappingService($serializer, $validator))
                ->map(new Request(content: '{}'), CommissionApiCalculationRequestDTO::class);
            self::fail('Expected CommissionApiRequestMappingException.');
        } catch (CommissionApiRequestMappingException $exception) {
            self::assertSame([
                'eventReference: This value should not be blank.',
                'currencyCode: This value should have exactly 3 characters.',
            ], $exception->getViolations());
        }
    }
}
