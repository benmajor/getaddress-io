<?php

namespace BenMajor\GetAddress\Model\Invoice;

class InvoiceItem
{
    private int $quantity;
    private float $unitPrice;
    private float $total;
    private string $details;

    public function __construct(
        string $details,
        int $quantity,
        float $unitPrice,
        float $total
    ) {
        $this->details = $details;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->total = $total;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}