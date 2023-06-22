<?php

declare(strict_types=1);

namespace App\ReadModel\Domain\Contact;

use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class ContactView
{
    /**
     * @Groups({"show"})
     */
    public string $id;

    public string $ownerId;

    /**
     * @OA\Property(example="2017-07-21 17:32:28")
     * @Groups({"show"})
     */
    public string $crDate;

    /**
     * @OA\Property(example="Name Surname")
     * @Groups({"show"})
     */
    public string $name;

    /**
     * @Groups({"show"})
     */
    public ?string $organization = null;

    /**
     * @Groups({"show"})
     */
    public string $email;

    /**
     * @OA\Property(example="+1.10000001")
     * @Groups({"show"})
     */
    public string $phone;

    /**
     * @Groups({"show"})
     */
    public string $address;

    /**
     * @Groups({"show"})
     */
    public string $city;

    /**
     * @Groups({"show"})
     */
    public string $state;

    /**
     * @Groups({"show"})
     */
    public string $zip;

    /**
     * @Groups({"show"})
     */
    public string $country;

    public function __construct(
        string $id,
        string $ownerId,
        string $crDate,
        string $name,
        ?string $organization,
        string $email,
        string $phone,
        string $address,
        string $city,
        string $state,
        string $zip,
        string $country
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->crDate = $crDate;
        $this->name = $name;
        $this->organization = $organization;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->country = $country;
    }
}
