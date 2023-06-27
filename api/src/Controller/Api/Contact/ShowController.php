<?php

declare(strict_types=1);

namespace App\Controller\Api\Contact;

use App\ReadModel\Domain\Contact\ContactFetcher;
use App\ReadModel\Domain\Contact\ContactView;
use App\Security\UserIdentity;
use Doctrine\DBAL\Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    /**
     * @Route(path="contacts/{id}", name="contacts.show", methods={"GET"}, requirements={"id"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}))
     *
     * @OA\Response(
     *     response="200",
     *     description="Success response",
     *     @OA\JsonContent(ref=@Model(type=ContactView::class, groups={"show"}))
     * )
     *
     * @OA\Tag(name="Contacts")
     *
     * @throws Exception
     */

    public function show(string $id, ContactFetcher $fetcher): Response
    {
        /** @var UserIdentity $loggedUser */
        $loggedUser = $this->getUser();

        $contactView = $fetcher->findByIdAndOwner($id, $loggedUser->getId());

        if(is_null($contactView)) {
            throw new NotFoundHttpException('Contact not found');
        }

        return $this->json($contactView, 200, [], ['groups' => ['show']]);
    }
}