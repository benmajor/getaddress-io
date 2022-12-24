<?php

namespace BenMajor\GetAddress\Model\Subscription;

class SubscriptionPlan
{
    public const TERM_MONTHLY = 'monhtly';
    public const TERM_ANNUAL = 'annual';

    private string $term;
    private int $dailyLimit1;
    private int $dailyLimit2;
    private int $amount;
    private bool $multiApplication;

    public function __construct(
        string $term,
        int $dailyLimit1,
        int $dailyLimit2,
        int $amount,
        bool $multiApplication
    ) {
        $this->term = $term;
        $this->dailyLimit1 = $dailyLimit1;
        $this->dailyLimit2 = $dailyLimit2;
        $this->amount = $amount;
        $this->multiApplication = $multiApplication;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function getDailyLookupLimit1(): int
    {
        return $this->dailyLimit1;
    }

    public function getDailyLookupLimit2(): int
    {
        return $this->dailyLimit2;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function isMultiApplication(): bool
    {
        return $this->multiApplication === true;
    }
}