<?php

namespace BenMajor\GetAddress\Model;

class Address
{
    private ?string $thoroughfare;
    private ?string $buildingName;
    private ?string $subBuildingName;
    private ?string $subBuildingNumber;
    private ?string $buildingNumber;
    private ?string $line1;
    private ?string $line2;
    private ?string $line3;
    private ?string $line4;
    private ?string $locality;
    private ?string $city;
    private ?string $county;
    private ?string $district;
    private ?string $country;
    private array $formatted;

    public function __construct($address)
    {
        $this->thoroughfare = empty($address->thoroughfare)
            ? null
            : $address->thoroughfare;

        $this->buildingName = empty($address->building_name)
            ? null
            : $address->building_name;

        $this->subBuildingName = empty($address->sub_building_name)
            ? null
            : $address->sub_building_name;

        $this->subBuildingNumber = empty($address->sub_building_number)
            ? null
            : $address->sub_building_number;

        $this->buildingNumber = empty($address->building_number)
            ? null
            : $address->building_number;

        $this->line1 = empty($address->line_1)
            ? null
            : $address->line_1;

        $this->line2 = empty($address->line_2)
            ? null
            : $address->line_2;

        $this->line3 = empty($address->line_3)
            ? null
            : $address->line_3;

        $this->line4 = empty($address->line_4)
            ? null
            : $address->line_4;

        $this->locality = empty($address->locality)
            ? null
            : $address->locality;

        $this->district = empty($address->district)
            ? null
            : $address->district;

        $this->city = $address->town_or_city;
        $this->county = $address->county;
        $this->country = $address->county;
        $this->formatted = $address->formatted_address;
    }

    public function getThoroughfare(): ?string
    {
        return $this->thoroughfare;
    }

    public function getBuildingName(): ?string
    {
        return $this->buildingName;
    }

    public function getSubBuildingName(): ?string
    {
        return $this->subBuildingName;
    }

    public function getSubBuildingNumber(): ?string
    {
        return $this->subBuildingNumber;
    }

    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function getLine3(): ?string
    {
        return $this->line3;
    }

    public function getLine4(): ?string
    {
        return $this->line4;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function toArray(): array
    {
        return [
            'thoroughfare' => $this->getThoroughfare(),
            'buildingName' => $this->getBuildingName(),
            'subBuilingName' => $this->getSubBuildingName(),
            'subBuildingNumber' => $this->getSubBuildingNumber(),
            'buildingNumber' => $this->getBuildingNumber(),
            'line1' => $this->getLine1(),
            'line2' => $this->getLine2(),
            'line3' => $this->getLine3(),
            'line4' => $this->getLine4(),
            'locality' => $this->getLocality(),
            'city' => $this->getCity(),
            'county' => $this->getCounty(),
            'district' => $this->getDistrict(),
            'country' => $this->getCountry()
        ];
    }

    public function format(): string
    {
        return implode(', ', array_filter($this->formatted));
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
