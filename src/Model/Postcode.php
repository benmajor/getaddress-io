<?php

namespace BenMajor\GetAddress\Model;

class Postcode
{
    private string $postcode;
    private string $outcode;
    private string $incode;

    public function __construct($postcode)
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
}
