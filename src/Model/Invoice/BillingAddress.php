<?php

namespace BenMajor\GetAddress\Model\Invoice;

class BillingAddress
{
    private ?string $line1;
    private ?string $line2;
    private ?string $line3;
    private ?string $line4;
    private ?string $line5;
    private ?string $line6;

    public function __construct(
        ?string $line1,
        ?string $line2,
        ?string $line3,
        ?string $line4,
        ?string $line5,
        ?string $line6
    ) {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->line4 = $line4;
        $this->line5 = $line5;
        $this->line6 = $line6;
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

    public function getLine5(): ?string
    {
        return $this->line5;
    }

    public function getLine6(): ?string
    {
        return $this->line6;
    }
}