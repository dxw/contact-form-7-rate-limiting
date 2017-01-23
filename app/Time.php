<?php

namespace Dxw\ContactForm7RateLimiting;

class Time
{
    public function now()
    {
        return $this->secondsAgo(0);
    }

    public function secondsAgo(int $seconds)
    {
        return $this->getTimestamp(time()-$seconds);
    }

    private function getTimestamp(int $time)
    {
        return gmstrftime('%Y-%m-%dT%H:%M:%S', $time);
    }
}
