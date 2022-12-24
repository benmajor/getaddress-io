<?php

namespace BenMajor\GetAddress\Model\Suggestion;

use BenMajor\GetAddress\Converter\DistanceConverter;

class NearestSuggestion extends Suggestion
{
    private float $distance;

    public function __construct(
        string $formatted,
        string $endpoint,
        string $id,
        float $distance
    ) {
        parent::__construct($formatted, $endpoint, $id, self::TYPE_NEAREST);

        $this->distance = $distance;
    }

    public function getDistance(?string $measurement = null): float
    {
        $converter = new DistanceConverter($this->distance);
        return $converter->to($measurement);
    }


}
