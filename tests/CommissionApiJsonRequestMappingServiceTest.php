<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\Exception\CommissionApiRequestMappingException;
use App\Commissioning\Service\CommissionApiJsonRequestMappingService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;

final class CommissionApiJsonRequestMappingServiceTest extends TestCase
{
    public function testValidJsonIsDeserializedAndValidatedIntoRequestedDto(): void
    {
        $mapper = $this->mapper();
        $request = Request::create(
            '/api/commissioning/calculation',
            'POST',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'eventReference' => 'event-1',
                'currencyCode' => 'USD',
                'basisMinorAmount' => 10_000,
                'planCode' => 'plan-1',
                'context' => ['channel' => 'api'],
            ], JSON_THROW_ON_ERROR),
        );

        $dto = $mapper->map($request, CommissionApiCalculationRequestDTO::class);

        self::assertInstanceOf(CommissionApiCalculationRequestDTO::class, $dto);
        self::assertSame('event-1', $dto->eventReference);
        self::assertSame('USD', $dto->currencyCode);
        self::assertSame(10_000, $dto->basisMinorAmount);
        self::assertSame('plan-1', $dto->planCode);
        self::assertSame(['channel' => 'api'], $dto->context);
    }

    public function testMalformedJsonBecomesStableMappingException(): void
    {
        $mapper = $this->mapper();
        $request = Request::create(
            '/api/commissioning/calculation',
            'POST',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"eventReference":',
        );

        try {
            $mapper->map($request, CommissionApiCalculationRequestDTO::class);
            self::fail('Expected mapping exception.');
        } catch (CommissionApiRequestMappingException $exception) {
            self::assertSame('Invalid Commissioning API request.', $exception->getMessage());
            self::assertCount(1, $exception->getViolations());
            self::assertStringContainsString(
                'Request body could not be mapped to '.CommissionApiCalculationRequestDTO::class,
                $exception->getViolations()[0],
            );
        }
    }

    public function testValidationViolationsAreReturnedWithPropertyPaths(): void
    {
        $mapper = $this->mapper();
        $request = Request::create(
            '/api/commissioning/calculation',
            'POST',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'eventReference' => '',
                'currencyCode' => 'US',
                'basisMinorAmount' => -1,
            ], JSON_THROW_ON_ERROR),
        );

        try {
            $mapper->map($request, CommissionApiCalculationRequestDTO::class);
            self::fail('Expected validation exception.');
        } catch (CommissionApiRequestMappingException $exception) {
            self::assertSame('Invalid Commissioning API request.', $exception->getMessage());
            self::assertGreaterThanOrEqual(3, count($exception->getViolations()));

            $violations = implode("\n", $exception->getViolations());
            self::assertStringContainsString('eventReference:', $violations);
            self::assertStringContainsString('currencyCode:', $violations);
            self::assertStringContainsString('basisMinorAmount:', $violations);
        }
    }

    private function mapper(): CommissionApiJsonRequestMappingService
    {
        return new CommissionApiJsonRequestMappingService(
            new Serializer([new ObjectNormalizer()], [new JsonEncoder()]),
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator(),
        );
    }
}
