<?php

namespace BenMajor\GetAddress\Model;

class Suggestion
{
    public const TYPE_ADDRESS = 'address';
    public const TYPE_PLACE = 'place';

    private string $formatted;
    private string $endpoint;
    private string $id;

    public function __construct(
        string $formatted,
        string $endpoint,
        string $id,
        string $type
    ) {
        $this->endpoint = $endpoint;
        $this->formatted = $formatted;
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->formatted;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isAddress(): bool
    {
        return $this->type === self::TYPE_ADDRESS;
    }

    public function isPlace(): bool
    {
        return $this->type === self::TYPE_PLACE;
    }

    public function __toString(): string
    {
        return $this->formatted;
    }
}