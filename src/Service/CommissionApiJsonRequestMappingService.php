<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\Exception\CommissionApiRequestMappingException;
use App\Commissioning\ServiceInterface\CommissionApiJsonRequestMappingServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionApiJsonRequestMappingService across typed collaborators and boundaries.
 */
final class CommissionApiJsonRequestMappingService implements CommissionApiJsonRequestMappingServiceInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * Maps the supplied external Commissioning request into its canonical typed application representation.
     */
    public function map(Request $request, string $dtoClass): object
    {
        $payload = trim($request->getContent());

        if ('' === $payload) {
            $payload = '{}';
        }

        try {
            $dto = $this->serializer->deserialize($payload, $dtoClass, 'json');
        } catch (\Throwable $exception) {
            throw new CommissionApiRequestMappingException([sprintf('Request body could not be mapped to %s: %s', $dtoClass, $exception->getMessage())]);
        }

        $violations = $this->validator->validate($dto);

        if (0 === count($violations)) {
            return $dto;
        }

        $messages = [];

        foreach ($violations as $violation) {
            $messages[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        throw new CommissionApiRequestMappingException($messages);
    }
}
