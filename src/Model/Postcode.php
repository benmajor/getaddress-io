<?php

namespace BenMajor\GetAddress\Model;

class Postcode
{
    private string $postcode;
    private string $outcode;
    private string $incode;
    private ?Location $location;

    public function __construct(string $postcode, ?Location $location = null)
    {
        $clean = strtoupper(preg_replace("/[^A-Za-z0-9]/", '', $postcode));

        $this->postcode = sprintf(
            '%s %s',
            substr($clean, 0, -3),
            substr($clean, -3)
        );

        list($outcode, $incode) = explode(' ', $this->postcode);

        $this->outcode = $outcode;
        $this->incode = $incode;
        $this->location = $location;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function getOutcode(): string
    {
        return $this->outcode;
    }

    public function getIncode(): string
    {
        return $this->incode;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }
}
