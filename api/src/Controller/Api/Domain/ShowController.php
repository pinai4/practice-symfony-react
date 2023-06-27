<?php

declare(strict_types=1);

namespace App\Controller\Api\Domain;

use App\ReadModel\Domain\Domain\DomainFetcher;
use App\ReadModel\Domain\Domain\DomainView;
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
     * @Route(path="domains/{id}", name="domains.show", methods={"GET"}, requirements={"id"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}))
     *
     * @OA\Response(
     *     response="200",
     *     description="Success response",
     *     @OA\JsonContent(ref=@Model(type=DomainView::class, groups={"show"}))
     * )
     *
     * @OA\Tag(name="Domains")
     *
     *
     * @throws Exception
     */

    public function showById(string $id, DomainFetcher $fetcher): Response
    {
        /** @var UserIdentity $loggedUser */
        $loggedUser = $this->getUser();

        $domainView = $fetcher->findByIdAndOwner($id, $loggedUser->getId());

        if(is_null($domainView)) {
            throw new NotFoundHttpException('Domain not found');
        }

        return $this->json($domainView, 200, [], ['groups' => ['show']]);
    }

    /**
     * @Route(path="domains/{name}", name="domains.show.name", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Success response",
     *     @OA\JsonContent(ref=@Model(type=DomainView::class, groups={"show"}))
     * )
     *
     * @OA\Tag(name="Domains")
     *
     * @throws Exception
     */
    public function showByName(string $name, DomainFetcher $fetcher): Response
    {
        /** @var UserIdentity $loggedUser */
        $loggedUser = $this->getUser();

        $domainView = $fetcher->findByNameAndOwner($name, $loggedUser->getId());

        if(is_null($domainView)) {
            throw new NotFoundHttpException('Domain not found');
        }

        return $this->json($domainView, 200, [], ['groups' => ['show']]);
    }
}