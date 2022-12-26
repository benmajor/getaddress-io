<?php

namespace BenMajor\GetAddress\Client;

use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use DateTimeInterface;
use InvalidArgumentException;

class AdminClient extends AbstractClient implements ClientInterface
{
    public const API_VERSION = 3;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->cache = new FilesystemAdapter();

        $this->client = new Client([
            'base_uri' => sprintf('https://api.getAddress.io/v%d/', self::API_VERSION)
        ]);

        $this->disableCaching();
    }

    public function getUsage(?DateTimeInterface $from = null, ?DateTimeInterface $to = null)
    {
        $endpoint = '/usage';

        if ($from !== null && $to !== null) {
            if ($from > $to) {
                throw new InvalidArgumentException('From date should be before to date.');
            }

            $endpoint .= sprintf(
                '/from/%d/%d/%d/To/%d/%d/%d/',
                $from->format('d'),
                $from->format('m'),
                $from->format('Y'),
                $to->format('d'),
                $to->format('m'),
                $to->format('Y')
            );
        }
        elseif ($from !== null) {
            $endpoint .= sprintf(
                '/%d/%d/%d/',
                $from->format('d'),
                $from->format('m'),
                $from->format('Y')
            );
        }

        print_r($this->getResponse($endpoint));
        die();
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
