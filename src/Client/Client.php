<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Exception;
use BenMajor\GetAddress\FindResponse;
use BenMajor\GetAddress\Model\Address;
use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Model\Filter\Filter;
use BenMajor\GetAddress\Model\Location;
use BenMajor\GetAddress\Model\Place;
use BenMajor\GetAddress\Model\Postcode;
use BenMajor\GetAddress\Model\Suggestion\NearestSuggestion;
use BenMajor\GetAddress\Model\Suggestion\Suggestion;
use BenMajor\GetAddress\Response;

class Client extends AbstractClient implements ClientInterface
{
    /**
     * Wrapper for the "find" endpoint:
     * https://documentation.getaddress.io/
     *
     * @param string $postcode
     * @param string|null $property
     *
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
     * @return Collection<int, Suggestion>
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
                    $suggestion->id,
                    Suggestion::TYPE_ADDRESS
                )
            );
        }

        return $collection;
    }

    /**
     * Get a list of places matching the specific query:
     * https://documentation.getaddress.io/Location
     *
     * @param string $term
     * @param Filter|null $filter
     * @param int $limit
     * @param Location|null $location
     * @return Collection<int, Suggestion>
     */
    public function location(
        string $term,
        ?Filter $filter = null,
        int $limit = 6,
        ?Location $location = null
    ): Collection {
        $endpoint = sprintf('location/%s', $term);

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
            [ 'top' => $limit ],
            $body
        );

        $collection = new Collection();

        foreach ($response->suggestions as $suggestion) {
            $collection->add(
                new Suggestion(
                    $suggestion->location,
                    $suggestion->url,
                    $suggestion->id,
                    Suggestion::TYPE_PLACE
                )
            );
        }

        return $collection;
    }

    /**
     * Retrieve a single location by its ID:
     * https://documentation.getaddress.io/Location (see Step 2)
     *
     * @param string $id
     * @return Place
     */
    public function getLocation(string $id): Place
    {
        $endpoint = sprintf('/get-location/%s', $id);
        $response = $this->getResponse($endpoint);

        // Hydrate the response:
        return new Place(
            $response->area,
            $response->town_or_city,
            $response->county,
            $response->country,
            new Postcode(
                $response->postcode,
                new Location(
                    $response->latitude,
                    $response->longitude
                )
            )
        );
    }

    /**
     * Retrieve typeahead results from GetAddress's API:
     * https://documentation.getaddress.io/Typeahead
     *
     * @param string $query
     * @param Filter|null $filter
     * @param integer|null $limit
     * @param array|null $fields
     * @return array
     */
    public function typeahead(
        string $query,
        ?Filter $filter = null,
        ?int $limit = null,
        ?array $fields = null
    ): array {
        $endpoint = sprintf('/typeahead/%s/', $query);
        $body = null;
        $params = null;
        $method = 'GET';

        if ($filter !== null || $fields !== null || $limit !== null) {
            $method = 'POST';

            if ($filter !== null) {
                $body['filter'] = $filter->toJson();
            }

            if ($fields !== null) {
                $body['search'] = $fields;
            }
        }

        if ($limit !== null) {
            $params['top'] = $limit;
        }

        $response = $this->getResponse(
            $endpoint,
            $method,
            $params,
            $body
        );

        return $response->response;
    }

    /**
     * Perform a reverse geocode lookup to find the nearest address for a given postcode:
     * https://documentation.getaddress.io/Nearest
     *
     * @param float $latitude
     * @param float $longitude
     * @param boolean $residentialOnly
     * @return Collection
     */
    public function nearest(
        float $latitude,
        float $longitude,
        ?int $limit = null,
        ?float $radius = null,
        ?bool $residentialOnly = false
    ): Collection {
        $endpoint = sprintf('/nearest/%s/%s', $latitude, $longitude);
        $params = [ ];
        $body = null;
        $method = 'GET';

        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        if ($radius !== null) {
            $params['radius'] = $radius;
        }

        if ($residentialOnly !== null) {
            $method = 'POST';

            $body['filter'] = [
                'residential' => ($residentialOnly === true)
                    ? 'true'
                    : 'false'
            ];
        }

        $response = $this->getResponse(
            $endpoint,
            $method,
            $params,
            $body
        );

        $collection = new Collection();

        foreach ($response->suggestions as $suggestion) {
            $collection->add(
                new NearestSuggestion(
                    $suggestion->address,
                    $suggestion->url,
                    $suggestion->id,
                    $suggestion->distance
                )
            );
        }

        return $collection;
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
