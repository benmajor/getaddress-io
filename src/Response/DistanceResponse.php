<?php

namespace BenMajor\GetAddress\Response;

use BenMajor\GetAddress\Model\Location;
use BenMajor\GetAddress\Model\Postcode;

class DistanceResponse extends AbstractResponse implements ResponseInterface
{
    public const MEASUREMENT_METRES = 'meter';
    public const MEASUREMENT_MILES = 'miles';
    public const MEASUREMENT_FEET = 'feet';
    public const MEASUREMENT_YARDS = 'yards';

    private Location $fromLocation;
    private Postcode $fromPostcode;
    private Location $toLocation;
    private Postcode $toPostcode;

    private float $meters;

    public function __construct(
        Postcode $fromPostcode,
        Location $fromLocation,
        Postcode $toPostcode,
        Location $toLocation,
        float $distance
    ) {
        $this->fromLocation = $fromLocation;
        $this->fromPostcode = $fromPostcode;
        $this->toLocation = $toLocation;
        $this->toPostcode = $toPostcode;
        $this->meters = $distance;
    }

    public function getFromLocation(): Location
    {
        return $this->fromLocation;
    }

    public function getFromPostcode(): Postcode
    {
        return $this->fromPostcode;
    }

    public function getToLocation(): Location
    {
        return $this->toLocation;
    }

    public function getToPostcode(): Postcode
    {
        return $this->toPostcode;
    }

    /**
     * Get the distance in the specified measurement
     *
     * @param string $unit
     * @return float
     */
    public function getDistance(string $unit = null): float
    {
        switch ($unit) {
            case self::MEASUREMENT_METRES:
            default:
                return $this->meters;

            case self::MEASUREMENT_MILES:
                return $this->meters * 0.000621371;

            case self::MEASUREMENT_FEET:
                return $this->meters * 3.28084;

            case self::MEASUREMENT_YARDS:
                return $this->meters * 1.09361;
        }
    }
}
