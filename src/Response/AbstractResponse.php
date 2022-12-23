<?php

namespace BenMajor\GetAddress\Response;

class AbstractResponse implements ResponseInterface
{
    private $json;

    public function __construct($json)
    {
        $this->json = $json;
    }

    public function getOriginalResponse()
    {
        return $this->json;
    }

    public function setOriginalResponse($json): self
    {
        $this->json = $json;

        return $this;
    }
}
