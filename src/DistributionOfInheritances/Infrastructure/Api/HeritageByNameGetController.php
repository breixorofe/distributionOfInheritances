<?php

namespace App\DistributionOfInheritances\Infrastructure\Api;

use App\DistributionOfInheritances\Application\HeritageQuery;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class HeritageByNameGetController
{

    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(string $name, Request $request): JsonResponse
    {
        try {
            $validator = Validation::createValidator();

            $constraint = new Assert\Collection([
                'name' => new Assert\Length(["min" => 2]),
                'birthday' => new Assert\Date(),
                'properties' => new Assert\Collection(['type' => new Assert\NotBlank()]),
                "sons" => new Assert\Collection([
                    'name' => new Assert\NotBlank(),
                    'birthday' => new Assert\Date(),
                    'properties' => new Assert\Collection(['type' => new Assert\NotBlank()]),
                ])
            ]);
            $validator->validate($request->toArray(), $constraint);
            $response = $this->queryBus->ask(new HeritageQuery($name, $request->toArray()));
            return new JsonResponse($response, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(explode(": ", $e->getMessage())[1], Response::HTTP_BAD_REQUEST);
        }
    }


}