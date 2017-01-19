<?php

namespace Dxw\ContactForm7RateLimiting;

class DatabaseReader
{
    public function __construct(Time $time, IP $ip, \wpdb $wpdb)
    {
        $this->time = $time;
        $this->ip = $ip;
        $this->wpdb = $wpdb;
    }

    public function recentSubmissions(int $seconds)
    {
        return (int) $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->wpdb->prefix}cf7_rate_limiting WHERE source=%s AND timestamp > %s",
            $this->ip->getSource(),
            $this->time->secondsAgo($seconds)
        ));
    }
}
