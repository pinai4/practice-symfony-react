<?php

declare(strict_types=1);

namespace App\ReadModel\Domain\Domain;

use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class DomainView
{
    /**
     * @Groups({"show"})
     */
    public string $id;
    public string $ownerId;
    /**
     * @Groups({"show"})
     */
    public string $name;
    /**
     * @OA\Property(example="2017-07-21 17:32:28")
     * @Groups({"show"})
     */
    public string $cr_date;
    /**
     * @OA\Property(example="2017-07-21 17:32:28")
     * @Groups({"show"})
     */
    public string $exp_date;

    public function __construct(string $id, string $ownerId, string $name, string $crDate, string $expDate)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->cr_date = $crDate;
        $this->exp_date = $expDate;
    }
}
