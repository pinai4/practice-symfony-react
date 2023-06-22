<?php

declare(strict_types=1);

namespace App\Controller\Api\Contact;

use App\Controller\Api\PaginationSerializer;
use App\ReadModel\Domain\Contact\ContactFetcher;
use App\ReadModel\Domain\Contact\ContactFilter;
use App\Security\UserIdentity;
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
     * @Route(path="/contacts", name="contacts", methods={"GET"})
     *
     * @OA\Parameter(
     *     name="filter[name]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[organization]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[email]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[phone]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[address]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[city]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[state]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[zip]",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string"),
     *     style="form"
     * )
     * @OA\Parameter(
     *     name="filter[country]",
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
     *
     * @OA\Response(
     *     response=200,
     *     description="Success response",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="items", type="array", @OA\Items(
     *             @OA\Property(property="id", type="string", example="e1358d57-99ec-4bab-8cc7-22baadb39c73"),
     *             @OA\Property(property="cr_date", type="string", example="2021-12-05 23:59:59"),
     *             @OA\Property(property="name", type="string", example="Ivan Petrov"),
     *             @OA\Property(property="organization", type="string", nullable=true, example="Some Company"),
     *             @OA\Property(property="email", type="string", example="test-default@email.com"),
     *             @OA\Property(property="phone", type="string", example="+1.12312323"),
     *             @OA\Property(property="address", type="string", example="Street 1"),
     *             @OA\Property(property="city", type="string", example="Kyiv"),
     *             @OA\Property(property="state", type="string", example="Kyivskaya oblast"),
     *             @OA\Property(property="zip", type="string", example="10001"),
     *             @OA\Property(property="country", type="string", example="UA"),
     *         )),
     *         @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
     *     )
     * ),
     *
     * @OA\Tag(name="Contacts")
     *
     * @throws ExceptionInterface
     */
    public function index(Request $request, ContactFetcher $fetcher): Response
    {
        $filter = $this->denormalizer->denormalize(
            $request->query->all()['filter'] ?? [],
            ContactFilter::class,
            'array'
        );

        /**
         * @var $user UserIdentity
         */
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
                'pagination' => PaginationSerializer::toArray($pagination)
            ],
            200
        );
    }
}