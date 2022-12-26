<?php

namespace BenMajor\GetAddress\Model\Usage;

use DateTimeInterface;

class DailyUsage
{
    private DateTimeInterface $date;
    private int $requestCount;
    private int $limit;
    private int $monthlyBuffer;
    private int $monthlyBufferUsed;

    public function __construct(
        DateTimeInterface $date,
        int $requestCount,
        int $limit,
        int $monthlyBuffer,
        int $monthlyBufferUsed
    ) {
        $this->date = $date;
        $this->requestCount = $requestCount;
        $this->limit = $limit;
        $this->monthlyBuffer = $monthlyBuffer;
        $this->monthlyBufferUsed = $monthlyBufferUsed;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }

    public function getUsageLimit(): int
    {
        return $this->limit;
    }

    public function getRemainingUsageLimit(): int
    {
        return $this->limit - $this->requestCount;
    }

    public function getMonthlyBuffer(): int
    {
        return $this->monthlyBuffer;
    }

    public function getMonthlyBufferUsed(): int
    {
        return $this->monthlyBufferUsed;
    }

    public function getRemainingMonthlyBuffer(): int
    {
        return $this->monthlyBuffer - $this->monthlyBufferUsed;
    }
}
