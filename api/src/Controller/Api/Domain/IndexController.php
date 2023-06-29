<?php

declare(strict_types=1);

namespace App\Controller\Api\Domain;

use App\Controller\Api\PaginationSerializer;
use App\ReadModel\Domain\Domain\DomainFetcher;
use App\ReadModel\Domain\Domain\DomainFilter;
use App\Security\UserIdentity;
use Doctrine\DBAL\Exception;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IndexController extends AbstractController
{
    private const PER_PAGE = 10;

    private DenormalizerInterface $denormalizer;

    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }

    /**
     * @Route(path="/domains", name="domains", methods={"GET"})
     * @OA\Parameter(
     *     name="filter[name]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="sort",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="direction",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string", enum={"asc", "desc"}),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer", example="1"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer", example="10"),
     *     style="form"
     * )
     * @OA\Response(
     *     response=200,
     *     description="Success response",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="items", type="array", @OA\Items(
     *             @OA\Property(property="id", type="string", example="b3003680-a2a5-45a1-a3a9-ead8341ec18a"),
     *             @OA\Property(property="name", type="string", example="example.com"),
     *             @OA\Property(property="cr_date", type="string", example="2021-12-05 23:59:59"),
     *             @OA\Property(property="exp_date", type="string", example="2026-12-05 23:59:59"),
     *             @OA\Property(property="contacts", type="array", @OA\Items(
     *                 @OA\Property(property="type", type="string", enum={"owner", "admin", "tech", "billing"}),
     *                 @OA\Property(property="id", type="string", example="b3003680-a2a5-45a1-a3a9-ead8341ec18a"),
     *             )),
     *         )),
     *         @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
     *     )
     * ),
     * @OA\Tag(name="Domains")
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function index(Request $request, DomainFetcher $fetcher): Response
    {
        $filter = $this->denormalizer->denormalize(
            $request->query->all()['filter'] ?? [],
            DomainFilter::class,
            'array'
        );

        /** @var UserIdentity $user */
        $user = $this->getUser();
        $pagination = $fetcher->all(
            $user->getId(),
            $filter,
            $request->query->getInt('page', 1),
            $request->query->getInt('per_page', self::PER_PAGE),
            $request->query->get('sort', null),
            $request->query->get('direction', null)
        );

        return $this->json(
            [
                'items' => $pagination->getItems(),
                'pagination' => PaginationSerializer::toArray($pagination),
            ],
            200
        );
    }
}
