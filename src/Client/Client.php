<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Exception;
use BenMajor\GetAddress\FindResponse;
use BenMajor\GetAddress\Model\Address;
use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\Filter\Filter;
use BenMajor\GetAddress\Model\Location;
use BenMajor\GetAddress\Model\Postcode;
use BenMajor\GetAddress\Model\Suggestion;
use BenMajor\GetAddress\Response;

class Client extends AbstractClient implements ClientInterface
{
    /**
     * Wrapper for the "find" endpoint:
     * https://documentation.getaddress.io/
     *
     * @param string $postcode
     * @param string|null $property
     * @return FindResponse
     */
    public function lookup(string $postcode, string $property = null): Response\FindResponse
    {
        if (empty($this->apiKey)) {
            throw new Exception\ApiKeyException('GetAddress.io API key cannot be empty.');
        }

        // Clean the postcode:
        $postcode = trim($postcode);

        if (empty($postcode)) {
            throw new Exception\RequestException('postcode parameter cannot be empty.');
        }

        $endpoint = ($property === null)
            ? sprintf('find/%s', $postcode)
            : sprintf('find/%s/%s', $postcode, $property);

        $getAddressResponse = $this->getResponse($endpoint);

        $postcode = new Postcode(
            $getAddressResponse->postcode,
            new Location(
                $getAddressResponse->latitude,
                $getAddressResponse->longitude
            )
        );

        $addresses = new Collection();

        foreach ($getAddressResponse->addresses as $gaAddress) {
            $addresses->add(new Address($gaAddress, $postcode));
        }

        return (new Response\FindResponse($postcode, $addresses))
            ->setOriginalResponse($getAddressResponse);
    }

    /**
     * Get a list of suggestions for a given search term:
     * https://documentation.getaddress.io/Autocomplete
     *
     * @param string $term
     * @return Collection
     */
    public function autocomplete(
        string $term,
        ?Filter $filter = null,
        int $limit = 6,
        ?Location $location = null,
        bool $returnAll = false
    ): Collection {
        $endpoint = sprintf('autocomplete/%s', $term);

        $method = 'GET';
        $body = null;

        if ($filter !== null || $location !== null) {
            $method = 'POST';

            if ($filter !== null) {
                $body['filter'] = $filter->toJson();
            }

            if ($location !== null) {
                $body['location'] = $location->toJson();
            }
        }

        $response = $this->getResponse(
            $endpoint,
            $method,
            [
                'top' => $limit,
                'all' => $returnAll ? 'true' : 'false'
            ],
            $body
        );

        $collection = new Collection();

        foreach ($response->suggestions as $suggestion) {
            $collection->add(
                new Suggestion(
                    $suggestion->address,
                    $suggestion->url,
                    $suggestion->id
                )
            );
        }

        return $collection;
    }

    public function location()
    {

    }

    public function typeahead()
    {

    }

    public function nearest()
    {

    }

    /**
     * Find the distance between two postcodes:
     * https://documentation.getaddress.io/Distance
     *
     * @param string $from
     * @param string $to
     * @return Response\DistanceResponse
     */
    public function distance(string $from, string $to): Response\DistanceResponse
    {
        $endpoint = sprintf('distance/%s/%s', $from, $to);

        $response = $this->getResponse($endpoint);

        $from = new Postcode(
            $response->from->postcode,
            new Location(
                $response->from->latitude,
                $response->from->longitude
            )
        );

        $to = new Postcode(
            $response->to->postcode,
            new Location(
                $response->to->latitude,
                $response->to->longitude
            )
        );

        return (new Response\DistanceResponse($from, $to, $response->metres))
            ->setOriginalResponse($response);
    }

    /**
     * Return a single address from GetAddress:
     *
     *
     * @param string $id
     * @return Address
     */
    public function getAddress(string $id): Address
    {
        $getAddressResponse = $this->getResponse(
            sprintf('get/%s', $id)
        );

        $postcode = new Postcode(
            $getAddressResponse->postcode,
            new Location(
                $getAddressResponse->latitude,
                $getAddressResponse->longitude
            )
        );

        return new Address($getAddressResponse, $postcode);
    }
}
