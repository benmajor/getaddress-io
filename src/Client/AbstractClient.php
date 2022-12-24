<?php

namespace BenMajor\GetAddress\Client;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException as HttpException;
use stdClass;

class AbstractClient implements ClientInterface
{
    private string $apiKey;

    private HttpClient $client;

    private int $cacheTtl = 86400;
    private bool $useCache = true;
    private FilesystemAdapter $cache;

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
     * @return stdClass
     */
    protected function getResponse(string $endpoint): stdClass
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
     * @return stdClass
     */
    protected function sendRequest(string $endpoint): stdClass
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