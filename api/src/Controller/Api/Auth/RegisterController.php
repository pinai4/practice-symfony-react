<?php

declare(strict_types=1);

namespace App\Controller\Api\Auth;

use App\Model\Auth\Command\User\Register\Command;
use App\Model\Auth\Command\User\Register\Handler;
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
     * @Route(path="auth/register", name="auth.register", methods={"POST"})
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"id", "name", "email", "password"},
     *         @OA\Property(property="id", type="string", format="uuid", example="dfb4c44c-b25e-4f8a-b4a2-b7300ae33599"),
     *         @OA\Property(property="name", type="string", example="Name Surname"),
     *         @OA\Property(property="email", type="string", example="example@example.com"),
     *         @OA\Property(property="password", type="string", example="secret")
     *     )
     * )
     * @OA\Response(
     *     response="201",
     *     description="Success response"
     * )
     * @OA\Response(
     *     response="409",
     *     description="Error",
     *     @OA\JsonContent(ref="#/components/schemas/Error409")
     * )
     * @OA\Response(
     *     response="422",
     *     description="Params Validation Errors",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="errors", type="object",
     *             @OA\Property(property="id", type="string", example="This is not a valid UUID."),
     *             @OA\Property(property="name", type="string", example="This value should not be blank."),
     *             @OA\Property(property="email", type="string", example="This value is not a valid email address."),
     *             @OA\Property(property="password", type="string", example="This value is too short. It should have 6 characters or more.")
     *         )
     *     )
     * )
     * @OA\Tag(name="Auth")
     */
    public function register(Request $request, Handler $handler): Response
    {
        $command = $this->serializer->deserialize($request->getContent(), Command::class, 'json');

        $this->validator->validate($command);

        $handler->handle($command);

        return $this->json([], 201);
    }
}
