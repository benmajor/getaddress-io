<?php

namespace BenMajor\GetAddress\Client;

use BenMajor\GetAddress\Exception\AuthenticationException;
use BenMajor\GetAddress\Exception\InvalidPostcodeException;
use BenMajor\GetAddress\Exception\LookupException;
use BenMajor\GetAddress\Exception\RateLimitException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException as HttpException;
use stdClass;

class AbstractClient implements ClientInterface
{
    private string $apiKey;

    private Client $client;

    private int $cacheTtl = 86400;
    private bool $useCache = true;
    private FilesystemAdapter $cache;

    private const NOCACHE_ENDPOINTS = [
        'autocomplete',
        'typeahead'
    ];

    public function __construct(string $apiKey)
    {
        $this->apiKey = trim($apiKey);
        $this->client = new Client([ 'base_uri' => 'https://api.getAddress.io/' ]);
        $this->cache = new FilesystemAdapter();
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
     * @return AbstractAdapter
     */
    public function getCache(): AbstractAdapter
    {
        return $this->cache;
    }

    /**
     * Get the response from GetAddress's API
     *
     * @param string $endpoint
     * @param string $method
     * @param array|null $params
     * @param array|null $body
     * @return stdClass
     */
    protected function getResponse(
        string $endpoint,
        string $method = 'GET',
        ?array $params = null,
        ?array $body = null
    ): stdClass {
        try {
            $endpointSegments = explode('/', $endpoint);

            $useCache = (in_array($endpointSegments[0], self::NOCACHE_ENDPOINTS) === false);

            if ($this->isCachingEnabled() === true && $useCache) {
                $cacheKey = strtolower(
                    str_replace([ '/', ' ' ], '_', trim($endpoint, '/'))
                );

                return $this->cache->get($cacheKey, function (ItemInterface $item) use ($endpoint, $method, $params, $body) {
                    $item->expiresAfter($this->cacheTtl);

                    return $this->sendRequest(
                        $endpoint,
                        $method,
                        $params,
                        $body
                    );
                });
            }
            else {
                return $this->sendRequest(
                    $endpoint,
                    $method,
                    $params,
                    $body
                );
            }
        } catch (HttpException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                case 401:
                    throw new AuthenticationException('GetAddress.io authentication failed, check API key.');
                    break;

                case 400:
                    throw new InvalidPostcodeException('Specified postcode parameter is invalid.');
                    break;

                case 403:
                    throw new RateLimitException('GetAddress.io rate limit exceeded.');
                    break;

                default:
                    throw new LookupException(
                        sprintf('GetAddress.io lookup failed: "%s".', $e->getMessage())
                    );
            }
        }
    }

    /**
     * Send the request via Guzzle to GetAddress's API
     *
     * @param string $endpoint
     * @return stdClass
     */
    protected function sendRequest(
        string $endpoint,
        string $method = 'GET',
        ?array $params = null,
        ?array $body = null
    ): stdClass {

        $requestParams = [
            'query' => ($params !== null && count($params))
                ? array_merge($params, [ 'api-key' => $this->apiKey, 'expand' => 'true' ])
                : [ 'api-key' => $this->apiKey, 'expand' => 'true' ]
        ];

        if ($body !== null && count($body)) {
            $requestParams['json'] = $body;
        }

        $response = $this->client->request(
            $method,
            $endpoint,
            $requestParams
        );

        return json_decode(
            $response->getBody()->getContents(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}