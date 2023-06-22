<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


class HomeController extends AbstractController
{
    /**
     * API Home
     *
     * @Route("", name="home", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Success response",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="name", type="string")
     *     )
     * )
     * @OA\Tag(name="API")
     *
     */
    public function home(): Response
    {
        return $this->json([
                               'name' => 'JSON API',
                           ]);
    }
}
