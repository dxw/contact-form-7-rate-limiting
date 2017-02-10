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
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        $version = (int)get_option('cf7_rate_limiting_version');
        if ($version < 1) {
            require($this->abspath.'/wp-admin/includes/upgrade.php');
            // Arguments to dbDelta untested
            // timestamp = 0000-00-00T00:00:00 = 23 bytes
            // source = 0000:0000:0000:0000:0000:0000:0000:0000/128 = 43 bytes
            dbDelta("
            CREATE TABLE {$this->wpdb->prefix}cf7_rate_limiting (
                id INT NOT NULL AUTO_INCREMENT,
                timestamp VARCHAR(23),
                source VARCHAR(43),
                PRIMARY KEY (id)
            ) {$this->wpdb->get_charset_collate()};
            ");

            update_option('cf7_rate_limiting_version', 1);
        }
    }
}
