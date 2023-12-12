<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Request
{
    private Client $client;

    private array $options = [];

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

    public function get(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('GET', $uri, $this->getOptions($options));
    }

    public function post(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('POST', $uri, $this->getOptions($options));
    }

    public function delete(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('DELETE', $uri, $this->getOptions($options));
    }

    public function put(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->client->request('PUT', $uri, $this->getOptions($options));
    }
}
