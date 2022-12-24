<?php

namespace BenMajor\GetAddress\Model\Subscription;

use DateTimeInterface;

class Subscription
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';

    public const PAYMENT_METHOD_DIRECT_DEBIT = 'direct_debt';
    public const PAYMENT_METHOD_PAYPAL = 'paypal';

    public DateTimeInterface $nextBillingDate;
    public DateTimeInterface $startDate;
    public string $status;
    public string $paymentMethod;
    public string $name;
    public SubscriptionPlan $plan;

    public function __construct(
        DateTimeInterface $nextBillingDate,
        DateTimeInterface $startDate,
        string $status,
        string $paymentMethod,
        string $name,
        SubscriptionPlan $plan
    ) {
        $this->nextBillingDate = $nextBillingDate;
        $this->startDate = $startDate;
        $this->status = $status;
        $this->paymentMethod = $paymentMethod;
        $this->name = $name;
        $this->plan = $plan;
    }

    public function getNextBillingDate(): DateTimeInterface
    {
        return $this->nextBillingDate;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function hasExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function getPaymentMethod(): string
    {
        $this->paymentMethod;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlan(): SubscriptionPlan
    {
        return $this->plan;
    }
}