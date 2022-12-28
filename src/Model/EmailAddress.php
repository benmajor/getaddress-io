<?php

namespace BenMajor\GetAddress\Model;

class EmailAddress
{
    private ?int $id;
    private string $emailAddress;
    private string $username;
    private string $domain;

    public function __construct(string $emailAddress, ?int $id = null)
    {
        $this->emailAddress = $emailAddress;
        list($this->username, $this->domain) = explode('@', $this->emailAddress);
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isValid(): bool
    {
        return filter_var($this->emailAddress, FILTER_VALIDATE_EMAIL);
    }

    public function __toString(): string
    {
        return $this->getEmailAddress();
    }
}
