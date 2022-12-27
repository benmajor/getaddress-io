<?php

namespace BenMajor\GetAddress\Model\Address;

use BenMajor\GetAddress\Model\Postcode;

class PrivateAddress
{
    private ?int $id;
    private ?string $line1;
    private ?string $line2;
    private ?string $line3;
    private ?string $line4;
    private ?string $locality;
    private ?string $city;
    private ?string $county;
    private ?Postcode $postcode;

    public function __construct(
        ?int $id = null,
        ?string $line1 = null,
        ?string $line2 = null,
        ?string $line3 = null,
        ?string $line4 = null,
        ?string $locality = null,
        ?string $city = null,
        ?string $county = null,
        ?Postcode $postcode = null
    ) {
        $this->setId($id);
        $this->setLine1($line1);
        $this->setLine2($line2);
        $this->setLine3($line3);
        $this->setLine4($line4);
        $this->setLocality($locality);
        $this->setCity($city);
        $this->setCounty($county);
        $this->setPostcode($postcode);
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLine1(string $line1): self
    {
        $line1 = trim($line1);

        $this->line1 = (empty($line1))
            ? null
            : $line1;

        return $this;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine2(string $line2): self
    {
        $line2 = trim($line2);

        $this->line2 = (empty($line2))
            ? null
            : $line2;

        return $this;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine3(string $line3): self
    {
        $line3 = trim($line3);

        $this->line3 = (empty($line3))
            ? null
            : $line3;

        return $this;
    }

    public function getLine3(): ?string
    {
        return $this->line3;
    }

    public function setLine4(string $line4): self
    {
        $line4 = trim($line4);

        $this->line4 = (empty($line4))
            ? null
            : $line4;

        return $this;
    }

    public function getLine4(): ?string
    {
        return $this->line4;
    }

    public function setLocality(string $locality): self
    {
        $localily = trim($locality);

        $this->locality = (empty($locality))
            ? null
            : $locality;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setCity(string $city): self
    {
        $city = trim($city);

        $this->city = (empty($city))
            ? null
            : $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCounty(string $county): self
    {
        $county = trim($county);

        $this->county = (empty($county))
            ? null
            : $county;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
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

    public function toArray(): array
    {
        $address = [ ];

        if ($this->id !== null) {
            $address['id'] = $this->id;
        }

        if ($this->line1 !== null) {
            $address['line1'] = $this->line1;
        }

        if ($this->line2 !== null) {
            $address['line2'] = $this->line2;
        }

        if ($this->line3 !== null) {
            $address['line3'] = $this->line3;
        }

        if ($this->line4 !== null) {
            $address['line4'] = $this->line4;
        }

        if ($this->locality !== null) {
            $address['locality'] = $this->locality;
        }

        if ($this->city !== null) {
            $address['townOrCity'] = $this->city;
        }

        if ($this->county !== null) {
            $address['county'] = $this->county;
        }

        return $address;
    }
}
