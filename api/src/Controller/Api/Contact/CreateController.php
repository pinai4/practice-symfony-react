<?php

declare(strict_types=1);

namespace App\Controller\Api\Contact;

use App\Model\Domain\Command\Contact\Create\Command;
use App\Model\Domain\Command\Contact\Create\Handler;
use App\Security\UserIdentity;
use App\Validator\Validator;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateController extends AbstractController
{
    private SerializerInterface $serializer;
    private Validator $validator;

    public function __construct(SerializerInterface $serializer, Validator $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(path="contacts", name="contacts.create", methods={"POST"})
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"id", "name", "email", "phone", "address", "city", "state", "zip", "country"},
     *         @OA\Property(property="id", type="string", format="uuid", example="14b01a9f-b890-4d38-b473-f6f7fcbfd1c9"),
     *         @OA\Property(property="name", type="string", example="Fname Lname"),
     *         @OA\Property(property="organization", type="string", example="My Company"),
     *         @OA\Property(property="email", type="string", example="test-new@example.com"),
     *         @OA\Property(property="phone", type="string", example="+7.0958888888"),
     *         @OA\Property(property="address", type="string", example="Street 1"),
     *         @OA\Property(property="city", type="string", example="Moscow"),
     *         @OA\Property(property="state", type="string", example="Moscowskay oblast"),
     *         @OA\Property(property="zip", type="string", example="30003"),
     *         @OA\Property(property="country", type="string", example="ru"),
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
     *             @OA\Property(property="name", type="string", example="This value is not valid."),
     *             @OA\Property(property="email", type="string", example="This value is not a valid email address."),
     *             @OA\Property(property="phone", type="string", example="This value is not valid."),
     *             @OA\Property(property="address", type="string", example="This value should not be blank."),
     *             @OA\Property(property="city", type="string", example="This value should not be blank."),
     *             @OA\Property(property="state", type="string", example="This value should not be blank."),
     *             @OA\Property(property="zip", type="string", example="This value should not be blank."),
     *             @OA\Property(property="country", type="string", example="This value should not be blank."),
     *         )
     *     )
     * )
     *
     * @OA\Tag(name="Contacts")
     */
    public function create(Request $request, Handler $handler): Response
    {
        $command = $this->serializer->deserialize($request->getContent(), Command::class, 'json');

        /** @var UserIdentity $user */
        $user = $this->getUser();
        $command->ownerId = $user->getId();

        $this->validator->validate($command);

        $handler->handle($command);

        return $this->json([], 201);
    }
}