<?php

namespace BenMajor\GetAddress\Response;

use BenMajor\GetAddress\Converter\DistanceConverter;
use BenMajor\GetAddress\Model\Postcode;

class DistanceResponse extends AbstractResponse implements ResponseInterface
{
    public const MEASUREMENT_METRES = 'meter';
    public const MEASUREMENT_MILES = 'miles';
    public const MEASUREMENT_FEET = 'feet';
    public const MEASUREMENT_YARDS = 'yards';

    private Postcode $from;
    private Postcode $to;
    private float $distance;

    public function __construct(Postcode $from, Postcode $to, float $distance)
    {
        $this->from = $from;
        $this->to = $to;
        $this->distance = $distance;
    }

    public function getFrom(): Postcode
    {
        return $this->from;
    }

    public function getTo(): Postcode
    {
        return $this->to;
    }
    /**
     * Get the distance in the specified measurement
     *
     * @param string $unit
     * @return float
     */
    public function getDistance(string $unit = null): float
    {
        $converter = new DistanceConverter($this->distance);
        return $converter->to($unit);
    }
}
