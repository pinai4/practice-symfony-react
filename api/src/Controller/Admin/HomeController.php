<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin")
 * @IsGranted("ROLE_ADMIN")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name=".home")
     */
    public function index(): Response
    {
        return new Response('Hello Admin');
    }
}
