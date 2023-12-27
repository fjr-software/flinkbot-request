<?php

declare(strict_types=1);

namespace FjrSoftware\Flinkbot\Request;

class Proxie
{
    /**
     * Constructor
     *
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct(
        private readonly string $host,
        private readonly string $username,
        private readonly string $password
    ) {
    }

    /**
     * Get URL
     *
     * @return string
     */
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
