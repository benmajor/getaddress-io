<?php

namespace BenMajor\GetAddress\Converter;

class DistanceConverter
{
    public const MEASUREMENT_METRES = 'meter';
    public const MEASUREMENT_MILES = 'miles';
    public const MEASUREMENT_FEET = 'feet';
    public const MEASUREMENT_YARDS = 'yards';

    private float $metres;

    public function __construct(float $metres)
    {
        $this->metres = $metres;
    }

    public function to(?string $measurement = null): float
    {
        switch ($unit) {
            case self::MEASUREMENT_METRES:
            default:
                return $this->meters;

            case self::MEASUREMENT_MILES:
                return self::toMiles($this->meters);

            case self::MEASUREMENT_FEET:
                return self::toFeet($this->meters);

            case self::MEASUREMENT_YARDS:
                return self::toYards($this->meters);
        }
    }

    public static function toMiles(float $metres): float
    {
        return $metres * 0.000621371;
    }

    public static function toFeet(float $metres): float
    {
        return $metres * 3.28084;
    }

    public static function toYards(float $metres): float
    {
        return $metres * 1.09361;
    }
}
