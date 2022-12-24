<?php

namespace BenMajor\GetAddress\Model\Filter;

use BenMajor\GetAddress\Model\Location;

class FilterRadius
{
    private float $distance;
    private Location $location;

    public function __construct(float $distance, Location $location)
    {
        $this->distance = $distance;
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }
}