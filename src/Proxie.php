<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

class Proxie
{
    public function __construct(
        private readonly string $host,
        private readonly string $username,
        private readonly string $password
    ) {
    }

    public function getUrl(): string
    {
        $url = parse_url($this->host);

        return sprintf(
            '%s://%s:%s@%s:%d',
            $url['scheme'],
            $this->username,
            $this->password,
            $url['host'],
            $url['port']
        );
    }
}
