<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Request
{
    private Client $client;

    private array $options = [];

    private Closure $callback;

    public function __construct(
        private readonly string $url,
        private readonly array $header = []
    ) {
        $this->init();
    }

    private function init(): void
    {
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 5.0,
        ]);

        $this->options = [
            RequestOptions::HEADERS => $this->header
        ];
    }

    public function setProxy(Proxie $proxie): void
    {
        $this->options += [
            RequestOptions::PROXY => [
                'http' => $proxie->getUrl(),
                'https' => $proxie->getUrl(),
            ]
        ];
    }

    private function getOptions(array $options): array
    {
        return $this->options + $options;
    }

    public function setCallbackRequest(callable $callback): void
    {
        $this->callback = $callback;
    }

    private function callbackRequest(ResponseInterface $response): ResponseInterface
    {
        if ($this->callback) {
            call_user_func($this->callback, $response);
        }

        return $response;
    }

    public function get(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('GET', $uri, $this->getOptions($options))
        );
    }

    public function post(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('POST', $uri, $this->getOptions($options))
        );
    }

    public function delete(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('DELETE', $uri, $this->getOptions($options))
        );
    }

    public function put(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->callbackRequest(
            $this->client->request('PUT', $uri, $this->getOptions($options))
        );
    }
}
