<?php

namespace BenMajor\GetAddress;

use BenMajor\GetAddress\Model\Location;
use BenMajor\GetAddress\Model\Postcode;
use BenMajor\GetAddress\Model\Address;
use BenMajor\GetAddress\Model\Collection;
use BenMajor\GetAddress\Response\ResponseInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter as Cache;
use Symfony\Contracts\Cache\ItemInterface;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException as HttpException;

class Client
{
    private string $apiKey;

    private HttpClient $client;

    private int $cacheTtl = 86400;
    private bool $useCache = true;
    private Cache $cache;

    public function __construct(string $apiKey)
    {
        $this->apiKey = trim($apiKey);
        $this->client = new HttpClient([ 'base_uri' => 'https://api.getAddress.io/' ]);
        $this->cache = new Cache();
    }

    /**
     * Set the cache time-to-live
     *
     * @param integer $ttl
     * @return self
     */
    public function setCacheTtl(int $ttl): self
    {
        $this->cacheTtl = $ttl;

        return $this;
    }

    /**
     * Get the current cache time-to-live
     *
     * @return integer
     */
    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    /**
     * Enable caching of results
     *
     * @return self
     */
    public function enableCaching(): self
    {
        $this->useCache = true;

        return $this;
    }

    /**
     * Disable caching of results
     *
     * @return self
     */
    public function disableCaching(): self
    {
        $this->useCache = false;

        return $this;
    }

    /**
     * Check to see if caching is currently enabled for responses
     *
     * @return boolean
     */
    public function isCachingEnabled(): bool
    {
        return $this->useCache;
    }

    /**
     * Get the cache
     *
     * @return Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }

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

        $addresses = new Collection();

        foreach ($getAddressResponse->addresses as $gaAddress) {
            $addresses->add(new Address($gaAddress));
        }

        return (new Response\FindResponse(
            new Postcode($getAddressResponse->postcode),
            new Location(
                $getAddressResponse->latitude,
                $getAddressResponse->longitude
            ),
            $addresses
        ))->setOriginalResponse($getAddressResponse);
    }

    public function distance(string $from, string $to): Response\DistanceResponse
    {
        $endpoint = sprintf('distance/%s/%s', $from, $to);

        $response = $this->getResponse($endpoint);

        return (new Response\DistanceResponse(
            new Postcode($response->from->postcode),
            new Location(
                $response->from->latitude,
                $response->from->longitude
            ),
            new Postcode($response->to->postcode),
            new Location(
                $response->to->latitude,
                $response->to->longitude
            ),
            $response->metres
        ))->setOriginalResponse($response);
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

        return new Address($getAddressResponse);
    }

    /**
     * Get the response from GetAddress's API
     *
     * @param string $endpoint
     * @return array
     */
    private function getResponse(string $endpoint)
    {
        try {
            if ($this->isCachingEnabled() === true) {
                $cacheKey = strtolower(
                    str_replace([ '/', ' ' ], '_', trim($endpoint, '/'))
                );

                return $this->cache->get($cacheKey, function (ItemInterface $item) use ($endpoint) {
                    $item->expiresAfter($this->cacheTtl);

                    return $this->sendRequest($endpoint);
                });
            }
            else {
                return $this->sendRequest($endpoint);
            }
        } catch (HttpException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                case 401:
                    throw new Exception\AuthenticationException('GetAddress.io authentication failed, check API key.');
                    break;

                case 400:
                    throw new Exception\InvalidPostcodeException('Specified postcode parameter is invalid.');
                    break;

                case 403:
                    throw new Exception\RateLimitException('GetAddress.io rate limit exceeded.');
                    break;

                default:
                    throw new Exception\LookupException(
                        sprintf('GetAddress.io lookup failed: "%s".', $e->getMessage())
                    );
            }
        }
    }

    /**
     * Send the request via Guzzle to GetAddress's API
     *
     * @param string $endpoint
     * @return array
     */
    private function sendRequest(string $endpoint)
    {
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'api-key' => $this->apiKey,
                'expand' => 'true'
            ]
        ]);

        return json_decode(
            $response->getBody()->getContents(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
