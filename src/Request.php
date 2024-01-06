<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Request
{
    /**
     * @var Client|null
     */
    private ?Client $client = null;

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var Closure|null
     */
    private ?Closure $callback = null;

    /**
     * Constructor
     *
     * @param string $url
     * @param array $header
     */
    public function __construct(
        private readonly string $url,
        private readonly array $header = []
    ) {
        $this->init();
    }

    /**
     * Initialize
     *
     * @return void
     */
    private function init(): void
    {
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 10,
        ]);

        $this->options = [
            RequestOptions::HEADERS => $this->header
        ];
    }

    /**
     * Set proxy
     *
     * @param Proxie $proxie
     * @return void
     */
    public function setProxy(Proxie $proxie): void
    {
        $this->options += [
            RequestOptions::PROXY => [
                'http' => $proxie->getUrl(),
                'https' => $proxie->getUrl(),
            ]
        ];
    }

    /**
     * Get options
     *
     * @param array $options
     * @return array
     */
    private function getOptions(array $options): array
    {
        return $this->options + $options;
    }

    /**
     * Set callback
     *
     * @param callable $callback
     * @return void
     */
    public function setCallbackRequest(callable $callback): void
    {
        $this->callback = $callback;
    }

    /**
     * Callback request
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function callbackRequest(ResponseInterface $response): ResponseInterface
    {
        if ($this->callback) {
            call_user_func($this->callback, $response);
        }

        return $response;
    }

    /**
     * Get
     *
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public function get(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('GET', $uri, $this->getOptions($options))
        );
    }

    /**
     * Post
     *
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public function post(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('POST', $uri, $this->getOptions($options))
        );
    }

    /**
     * Delete
     *
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public function delete(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('DELETE', $uri, $this->getOptions($options))
        );
    }

    /**
     * Put
     *
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public function put(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('PUT', $uri, $this->getOptions($options))
        );
    }
}
