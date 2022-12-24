<?php

namespace BenMajor\GetAddress\Model;

class Suggestion
{
    private string $formatted;
    private string $endpoint;
    private string $id;

    public function __construct(string $formatted, string $endpoint, string $id)
    {
        $this->endpoint = $endpoint;
        $this->formatted = $formatted;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFormattedAddress(): string
    {
        return $this->fomatted;
    }
}