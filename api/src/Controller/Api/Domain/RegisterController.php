<?php

declare(strict_types=1);

namespace App\Controller\Api\Domain;

use App\Model\Domain\Command\Domain\Register\Command;
use App\Model\Domain\Command\Domain\Register\Handler;
use App\Security\UserIdentity;
use App\Validator\Validator;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegisterController extends AbstractController
{
    private SerializerInterface $serializer;
    private Validator $validator;

    public function __construct(SerializerInterface $serializer, Validator $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(path="domains", name="domains.register", methods={"POST"})
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"id", "name", "period"},
     *         @OA\Property(property="id", type="string", format="uuid", example="14b01a9f-b890-4d38-b473-f6f7fcbfd1c9"),
     *         @OA\Property(property="name", type="string", example="example-domain.com"),
     *         @OA\Property(property="period", type="integer", minimum="1", maximum="10", example="1"),
     *         @OA\Property(property="owner_contact_id", type="string", format="uuid", example="14b01a9f-b890-4d38-b473-f6f7fcbfd1c9")
     *     )
     * )
     *
     * @OA\Response(
     *     response="201",
     *     description="Success response"
     * )
     *
     * @OA\Response(
     *     response="409",
     *     description="Error",
     *     @OA\JsonContent(ref="#/components/schemas/Error409")
     * )
     *
     * @OA\Response(
     *     response="422",
     *     description="Params Validation Errors",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="errors", type="object",
     *             @OA\Property(property="id", type="string", example="This is not a valid UUID."),
     *             @OA\Property(property="name", type="string", example="This value is not a valid hostname."),
     *             @OA\Property(property="period", type="string", example="This value should be between 1 and 10."),
     *             @OA\Property(property="owner_contact_id", type="string", example="This is not a valid UUID.")
     *         )
     *     )
     * )
     *
     * @OA\Tag(name="Domains")
     */
    public function register(Request $request, Handler $handler): Response
    {
        $command = $this->serializer->deserialize($request->getContent(), Command::class, 'json');

        /**
         * @var $user UserIdentity
         */
        $user = $this->getUser();
        $command->ownerId = $user->getId();

        $this->validator->validate($command);

        $handler->handle($command);

        return $this->json([], 201);
    }
}