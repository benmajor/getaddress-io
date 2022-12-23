<?php

namespace BenMajor\GetAddress\Response;

interface ResponseInterface
{
    public function getOriginalResponse();
    public function setOriginalResponse($json);
}
