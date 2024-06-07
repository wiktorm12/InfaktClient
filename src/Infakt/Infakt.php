<?php

declare(strict_types=1);

namespace Infakt;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Infakt\Exception\LogicException;
use Infakt\Repository\AbstractObjectRepository;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Infakt.
 */
class Infakt
{
    protected $api_endpoint;

    const API_VERSION = 'v3';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Infakt constructor.
     */
    public function __construct(string $apiKey, ClientInterface $client = null, bool $prod = true)
    {
        $this->api_endpoint = $prod ? 'https://api.infakt.pl' :'https://api.sandbox-infakt.pl';

        $this->apiKey = $apiKey;
        $this->client = $client instanceof ClientInterface ? $client : new Client();
    }

    /**
     * Return object repository for a specific model class name.
     *
     * @param $className
     *
     * @throws LogicException
     */
    public function getRepository(string $className): AbstractObjectRepository
    {
        $className = 'Infakt\\Repository\\'.\substr($className, \strrpos($className, '\\') + 1).'Repository';

        if (!\class_exists($className)) {
            throw new LogicException("There is no repository to work with class $className.");
        }

        return new $className($this);
    }

    /**
     * Send HTTP GET request.
     */
    public function get(string $query): ResponseInterface
    {
        return $this->request('get', $query);
    }

    /**
     * Send HTTP DELETE request.
     */
    public function delete(string $query): ResponseInterface
    {
        return $this->request('delete', $query);
    }

    /**
     * Send HTTP POST request.
     */
    public function post(string $query, ?string $body = null): ResponseInterface
    {
        return $this->request('post', $query, $body);
    }

    /**
     * Attach endpoint URL to the query.
     */
    public function buildQuery(string $query): string
    {
        return $this->api_endpoint.'/'.self::API_VERSION.'/'.$query;
    }

    /**
     * Prepare and perform HTTP request through the client.
     */
    public function request(string $method, string $query, ?string $body = null): ResponseInterface
    {
        $options = [
            'headers' => [
                'X-inFakt-ApiKey' => $this->apiKey,
            ],
        ];

        if ($body) {
            $options['body'] = $body;
        }

        return $this->client->request($method, $this->buildQuery($query), $options);
    }
}
