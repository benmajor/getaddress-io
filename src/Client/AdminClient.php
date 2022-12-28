<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Model\Address\PrivateAddress;
use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\EmailAddress;
use BenMajor\GetAddress\Model\Invoice\BillingAddress;
use BenMajor\GetAddress\Model\Invoice\Invoice;
use BenMajor\GetAddress\Model\Invoice\InvoiceItem;
use BenMajor\GetAddress\Model\Subscription\Subscription;
use BenMajor\GetAddress\Model\Subscription\SubscriptionPlan;
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

    /**
     * Update the account's primary email address:
     * https://documentation.getaddress.io/EmailAddress
     *
     * @param EmailAddress $email
     * @return boolean
     */
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

    /**
     * Retrieve a collection of invoices between the specified dates:
     * https://documentation.getaddress.io/Invoices
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @return Collection
     */
    public function getInvoices(DateTimeInterface $from, DateTimeInterface $to): Collection
    {
        $endpoint = sprintf(
            '/invoices/from/%d/%d/%d/to/%d/%d/%d',
            $from->format('d'),
            $from->format('m'),
            $from->format('Y'),
            $to->format('d'),
            $to->format('m'),
            $to->format('Y')
        );

        $response = $this->getResponse($endpoint);
        $collection = new Collection();

        foreach ($response->response as $inv) {
            $date = new DateTime($inv->date);
            $billingAddress = new BillingAddress(
                $inv->address_1,
                $inv->address_2,
                $inv->address_3,
                $inv->address_4,
                $inv->address_5,
                $inv->address_6
            );

            $items = new Collection();

            foreach ($inv->invoice_lines as $item) {
                $items->add(
                    new InvoiceItem(
                        $item->details,
                        $item->quantity,
                        $item->unit_price,
                        $item->subtotal
                    )
                );
            }

            $collection->add(
                new Invoice(
                    $date,
                    $billingAddress,
                    $inv->number,
                    $inv->total,
                    $inv->tax,
                    $inv->pdf_url,
                    $items
                )
            );
        }

        return $collection;
    }

    /**
     * Retrieve the specified invoice:
     * https://documentation.getaddress.io/Invoices
     *
     * @param string $number
     * @return Invoice
     */
    public function getInvoice(string $number): Invoice
    {
        $inv = $this->getResponse(
            sprintf('/invoices/%s', $number)
        );

        $date = new DateTime($inv->date);
        $billingAddress = new BillingAddress(
            $inv->address_1,
            $inv->address_2,
            $inv->address_3,
            $inv->address_4,
            $inv->address_5,
            $inv->address_6
        );

        $items = new Collection();

        foreach ($inv->invoice_lines as $item) {
            $items->add(
                new InvoiceItem(
                    $item->details,
                    $item->quantity,
                    $item->unit_price,
                    $item->subtotal
                )
            );
        }

        return new Invoice(
            $date,
            $billingAddress,
            $inv->number,
            $inv->total,
            $inv->tax,
            $inv->pdf_url,
            $items
        );
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
        $response = $this->getResponse('/subscription');

        $plan = new SubscriptionPlan(
            $response->plan->term,
            $response->plan->daily_lookup_limit_1,
            $response->plan->daily_lookup_limit_2,
            $response->plan->amount,
            $response->plan->multi_application
        );

        $startDate = DateTime::createFromFormat(
            'm/d/ Y h:i:s A',
            $response->start_date
        );

        $nextBillingDate = DateTime::createFromFormat(
            'm/d/ Y h:i:s A',
            $response->next_billing_date
        );

        return new Subscription(
            $nextBillingDate,
            $startDate,
            $response->status,
            $response->payment_method,
            $response->name,
            $plan
        );
    }
}
