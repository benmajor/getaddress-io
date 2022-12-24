<?php

namespace BenMajor\GetAddress\Model\Filter;

use BenMajor\GetAddress\Model\Postcode;

class Filter
{
    private ?string $county;
    private ?string $country;
    private ?string $area;
    private ?string $city;
    private ?Postcode $postcode;
    private ?string $outcode;
    private ?FilterRadius $radius;

    public function setCounty(?string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->getCount();
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setPostcode(?Postcode $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPostcode(): ?Postcode
    {
        return $this->postcode;
    }

    public function setOutcode(?string $outcode): self
    {
        $this->outcode = $outcode;

        return $this;
    }

    public function getOutcode(): ?string
    {
        return $this->outcode;
    }

    public function setRadius(?FilterRadius $radius): self
    {
        $this->radius = $radius;

        return $this;
    }

    public function getRadius(): ?FilterRadius
    {
        return $this->radius;
    }

    public function toJson(): array
    {
        $json = [];

        if ($this->city !== null) {
            $json['town_or_city'] = $this->city;
        }

        if ($this->county !== null) {
            $json['county'] = $this->county;
        }

        if ($this->country !== null) {
            $json['country'] = $this->country;
        }

        if ($this->area !== null) {
            $json['area'] = $this->area;
        }

        if ($this->postcode !== null) {
            $json['postcode'] = $this->postcode->getPostcode();
        }

        if ($this->outcode !== null) {
            $json['outcode'] = $this->outcode;
        }

        if ($this->radius !== null) {
            $json['radius'] = [
                'km' => $this->radius->getDistance(),
                'latitude' => $this->radius->getLocation()->getLatitude(),
                'longitude' => $this->radius->getLocation()->getLongitude()
            ];
        }

        return $json;
    }
}