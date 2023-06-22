<?php

declare(strict_types=1);

namespace App\Controller\Api\Profile;

use App\Security\UserIdentity;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ShowController extends AbstractController
{
    /**
     * User Profile
     *
     * @Route(path="/profile", name="profile", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Success response",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="name", type="string", description="User Name")
     *     )
     * )
     *
     * @OA\Tag(name="Profile")
     *
     */
    public function show(Security $security): Response
    {
        /**
         * @var $loggedUser UserIdentity
         */
        $loggedUser = $security->getUser();
        return $this->json(['name' => $loggedUser->getName()]);
    }
}