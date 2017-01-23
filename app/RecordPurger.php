<?php

namespace Dxw\ContactForm7RateLimiting;

class RecordPurger implements \Dxw\Iguana\Registerable
{
    public function __construct(\wpdb $wpdb, \Dxw\ContactForm7RateLimiting\Time $time)
    {
        $this->wpdb = $wpdb;
        $this->time = $time;
    }

    public function register()
    {
        if (!wp_next_scheduled('ContactForm7RateLimiting_purge_records')) {
            wp_schedule_event(time(), 'daily', 'ContactForm7RateLimiting_purge_records');
        }

        add_action('ContactForm7RateLimiting_purge_records', [$this, 'purgeRecords']);
    }

    public function purgeRecords()
    {
        $time = $this->time->secondsAgo(24*60*60);
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->wpdb->prefix}cf7_rate_limiting WHERE timestamp < %s", $time));
    }
}
