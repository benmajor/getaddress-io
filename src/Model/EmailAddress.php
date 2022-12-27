<?php

namespace BenMajor\GetAddress\Model;

class EmailAddress
{
    private string $emailAddress;
    private string $username;
    private string $domain;

    public function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
        list($this->username, $this->domain) = explode('@', $this->emailAddress);
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
