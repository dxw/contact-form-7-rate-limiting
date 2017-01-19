<?php

namespace Dxw\ContactForm7RateLimiting;

class IP
{
    public function __construct(\Dxw\Iguana\Value\Server $__server)
    {
        $this->server = $__server;
    }

    public function getSource()
    {
        return $this->server['REMOTE_ADDR'];
    }
}
