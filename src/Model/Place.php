<?php

namespace BenMajor\GetAddress\Model;

class Place
{
    private Location $location;
    private string $area;
    private string $city;
    private string $county;
    private string $country;
    private Postcode $postcode;

    public function __construct(
        Location $location,
        string $area,
        string $city,
        string $county,
        string $country,
        Postcode $postcode
    ) {
        $this->location = $location;
        $this->area = $area;
        $this->city = $city;
        $this->county = $county;
        $this->country = $country;
        $this->postcode = $postcode;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCounty(): string
    {
        return $this->county;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPostcode(): Postcode
    {
        return $this->postcode;
    }
}