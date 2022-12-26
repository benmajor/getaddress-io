<?php

namespace BenMajor\GetAddress\Model\Usage;

use DateTimeInterface;

class Usage
{
    private DateTimeInterface $date;
    private int $limit;
    private int $requestCount;

    public function __construct(DateTimeInterface $date, int $limit, int $requestCount)
    {
        $this->date = $date;
        $this->limit = $limit;
        $this->requestCount = $requestCount;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }
}
