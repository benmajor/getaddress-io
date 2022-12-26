<?php

namespace BenMajor\GetAddress\Client;

use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use DateTimeInterface;

class AdminClient extends AbstractClient implements ClientInterface
{
    public const API_VERSION = 3;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => sprintf('https://api.getAddress.io/v%d/', self::API_VERSION)
        ]);

        $this->cache = new FilesystemAdapter();
    }

    public function getUsage(DateTimeInterface $from, ?DateTimeInterface $to = null
    )
    {

    }

    public function addAddress()
    {

    }

    public function getEmailAddress()
    {

    }

    public function setEmailAddress()
    {

    }

    public function getInvoices()
    {

    }

    public function getInvoice($number)
    {

    }

    public function getInvoiceEmailRecipients()
    {

    }

    public function getInvoiceEmailRecipient(int $id)
    {

    }

    public function addInvoiceEmailRecipient(string $email)
    {

    }

    public function deleteInvoiceEmailRecipient(int $id)
    {

    }

    public function getExpiredInvoiceEmailRecipients()
    {

    }

    public function getExpiredInvoiceEmailRecipient(int $id)
    {

    }

    public function addExpiredInvoiceEmailRecipient(string $email)
    {

    }

    public function deleteExpiredInvoiceEmailRecipient(int $id)
    {

    }

    public function getSubscription()
    {

    }
}
