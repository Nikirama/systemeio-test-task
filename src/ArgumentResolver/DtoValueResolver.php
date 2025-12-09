<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return str_contains($argument->getType() ?? '', 'App\\Dto\\');
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = $request->getContent();
        $dtoClass = $argument->getType();

        try {
            $dto = $this->serializer->deserialize($data, $dtoClass, 'json');
        } catch (NotNormalizableValueException $e) {
            // Extract property name from exception
            $path = $e->getPath();
            $message = sprintf('Field "%s" has invalid type.', $path ?? 'unknown');

            throw new BadRequestHttpException(json_encode([
                'status' => 'error',
                'errors' => [
                    $path ?? 'field' => [$message]
                ],
            ]));
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new BadRequestHttpException(json_encode([
                'errors' => $errorMessages
            ]));
        }

        yield $dto;
    }
}
