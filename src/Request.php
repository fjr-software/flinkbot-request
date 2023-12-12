<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Request
{
    private Client $client;

    public function __construct(
        private readonly string $url,
        private readonly array $header = []
    ) {
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 5.0,
        ]);
    }

    private function options(array $options): array
    {
        return [
            [RequestOptions::HEADERS => $this->header],
            $options
        ];
    }

    public function get(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('GET', $uri, $this->options($options));
    }

    public function post(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('POST', $uri, $this->options($options));
    }

    public function delete(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('DELETE', $uri, $this->options($options));
    }

    public function put(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('PUT', $uri, $this->options($options));
    }
}
