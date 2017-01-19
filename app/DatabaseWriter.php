<?php

namespace Dxw\ContactForm7RateLimiting;

class DatabaseWriter
{
    public function __construct(Time $time, IP $ip, \wpdb $wpdb)
    {
        $this->time = $time;
        $this->ip = $ip;
        $this->wpdb = $wpdb;
    }

    public function recordSubmission()
    {
        $this->wpdb->insert($this->wpdb->prefix.'cf7_rate_limiting', [
            'timestamp' => $this->time->now(),
            'source' => $this->ip->getSource(),
        ]);
    }
}
