<?php

namespace BenMajor\GetAddress\Model\Invoice;

use BenMajor\GetAddress\Model\Collection;
use DateTimeInterface;
use DateTimeImmutable;

class Invoice
{
    private DateTimeInterface $date;
    private BillingAddress $billingAddress;
    private string $number;
    private float $total;
    private float $tax;
    private string $pdfUrl;
    private Collection $items;

    public function __construct(
        string $date,
        BillingAddress $billingAddress,
        string $number,
        float $total,
        float $tax,
        string $pdfUrl,
        Collection $items
    ) {
        $this->billingAddress = $billingAddress;
        $this->number = $number;
        $this->total = $total;
        $this->tax = $tax;
        $this->pdfUrl = $pdfUrl;
        $this->items = $items;

        $this->date = DateTimeImmutable::createFromFormat(
            'd/m/ Y h:i:s A',
            $date
        );
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getBillingAddress(): BillingAddress
    {
        return $this->billingAddress;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getPdfUrl(): string
    {
        return $this->pdfUrl;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}