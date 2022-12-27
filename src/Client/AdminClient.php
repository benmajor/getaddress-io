<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\Usage\DailyUsage;
use BenMajor\GetAddress\Model\Usage\Usage;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

class AdminClient extends AbstractClient implements ClientInterface
{
    public const API_VERSION = 3;

    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey);
        $this->disableCaching();
    }

    public function getDailyUsage(?DateTimeInterface $date = null): DailyUsage
    {
        $endpoint = sprintf('/v%d/usage', self::API_VERSION);

        if ($date !== null) {
            $endpoint .= sprintf(
                '/from/%d/%d/%d/',
                $date->format('d'),
                $date->format('m'),
                $date->format('Y'),
            );
        }

        $response = $this->getResponse($endpoint);

        return new DailyUsage(
            $date ?? new DateTime(),
            $response->usage_today,
            $response->daily_limit,
            $response->monthly_buffer,
            $response->monthly_buffer_used
        );
    }

    /**
     * Get account usage limit for the specified date range
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @return Collection
     */
    public function getUsage(DateTimeInterface $from, DateTimeInterface $to): Collection
    {
        if ($from > $to) {
            throw new InvalidArgumentException('From date should be before to date.');
        }

        $endpoint = sprintf(
            '/v%d/usage/from/%d/%d/%d/to/%d/%d/%d/',
            self::API_VERSION,
            $from->format('d'),
            $from->format('m'),
            $from->format('Y'),
            $to->format('d'),
            $to->format('m'),
            $to->format('Y')
        );

        // Get the results:
        $response = $this->getResponse($endpoint);

        $collection = new Collection();

        foreach ($response->response as $item) {
            $collection->add(
                new Usage(
                    new DateTime($item->date),
                    $item->limit,
                    $item->count
                )
            );
        }

        return $collection;
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
