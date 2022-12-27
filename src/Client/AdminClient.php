<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Model\Address\PrivateAddress;
use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\EmailAddress;
use BenMajor\GetAddress\Model\Subscription\Subscription;
use BenMajor\GetAddress\Model\Usage\DailyUsage;
use BenMajor\GetAddress\Model\Usage\Usage;
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

    // public function getPrivateAddresses(): Collection
    // {

    // }

    // public function getPrivateAddress(int $id): PrivateAddress
    // {

    // }

    // public function addPrivateAddress(PrivateAddress $address): PrivateAddress
    // {

    // }

    // public function deletePrivateAddress(PrivateAddress $address): bool
    // {

    // }

    /**
     * Get the acocunt's primary email address:
     * https://documentation.getaddress.io/EmailAddress
     *
     * @return EmailAddress
     */
    public function getEmailAddress(): EmailAddress
    {
        $response = $this->getResponse('/email-address');

        return new EmailAddress($response->{'email-address'});
    }


    public function setEmailAddress(EmailAddress $email): bool
    {
        if ($email->isValid() === false) {
            throw new InvalidArgumentException('Specified email address is invalid.');
        }

        $response = $this->getResponse(
            '/email-address',
            'PUT',
            null,
            [
                'new-email-address' => $email->getEmailAddress()
            ]
        );

        return true;
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

    public function getSubscription(): Subscription
    {

    }
}
