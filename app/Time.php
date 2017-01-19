<?php

namespace Dxw\ContactForm7RateLimiting;

class Time
{
    public function now()
    {
        return gmstrftime('%Y-%m-%dT%H:%M:%S');
    }
}
