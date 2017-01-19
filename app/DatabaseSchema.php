<?php

namespace Dxw\ContactForm7RateLimiting;

class DatabaseSchema implements \Dxw\Iguana\Registerable
{
    public function __construct(string $abspath, \wpdb $wpdb)
    {
        $this->abspath = $abspath;
        $this->wpdb = $wpdb;
    }

    public function register()
    {
        register_activation_hook(dirname(__DIR__).'/contact-form-7-rate-limiting.php', [$this, 'activationHook']);
    }

    public function activationHook()
    {
        require($this->abspath.'/wp-admin/includes/upgrade.php');
        // Arguments to dbDelta untested
        // timestamp = 0000-00-00T00:00:00 = 23 bytes
        // source = 0000:0000:0000:0000:0000:0000:0000:0000/128 = 43 bytes
        dbDelta("
        CREATE TABLE {$this->wpdb->prefix}cf7_rate_limiting (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            timestamp varchar(23),
            source varchar(43),
        )
        ");
    }
}
