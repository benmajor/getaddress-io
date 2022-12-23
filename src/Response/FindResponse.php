<?php

namespace BenMajor\GetAddress\Response;

use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\Postcode;
use BenMajor\GetAddress\Model\Location;

class FindResponse extends AbstractResponse implements ResponseInterface
{
    private Postcode $postcode;
    private Location $location;
    private Collection $addresses;

    public function __construct(
        Postcode $postcode,
        Location $location,
        Collection $addresses
    )  {
        $this->postcode = $postcode;
        $this->location = $location;
        $this->addresses = $addresses;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }
}
