<?php

namespace BenMajor\GetAddress\Client;

use Symfony\Component\Cache\Adapter\AbstractAdapter;

interface ClientInterface
{
    public function __construct(string $apiKey);
    public function setCacheTtl(int $ttl): self;
    public function getCacheTtl(): int;
    public function enableCaching(): self;
    public function disableCaching(): self;
    public function isCachingEnabled(): bool;
    public function getCache(): AbstractAdapter;

}