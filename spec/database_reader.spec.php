<?php

describe(\Dxw\ContactForm7RateLimiting\DatabaseReader::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->time = \Mockery::mock(\Dxw\ContactForm7RateLimiting\Time::class, function ($mock) {
            $mock->shouldReceive('secondsAgo')->with(300)->andReturn('2000-01-01T00:00:00');
        });
        $this->ip = \Mockery::mock(\Dxw\ContactForm7RateLimiting\IP::class, function ($mock) {
            $mock->shouldReceive('getSource')->andReturn('2001:db8::/64');
        });
        $this->wpdb = \Mockery::mock(\wpdb::class, function ($mock) {
            $mock->prefix = 'wp_';
        });

        $this->databaseReader = new \Dxw\ContactForm7RateLimiting\DatabaseReader(
            $this->time, $this->ip, $this->wpdb
        );
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    describe('->recentSubmissions()', function () {
        it('returns number of recent submissions', function () {
            $this->wpdb->shouldReceive('prepare')->with(
                'SELECT COUNT(*) FROM wp_cf7_rate_limiting WHERE source=%s AND timestamp > %s',
                '2001:db8::/64',
                '2000-01-01T00:00:00'
            )->andReturn('CORRECT SQL');

            $this->wpdb->shouldReceive('get_var')->with('CORRECT SQL')->andReturn('3');
            expect($this->databaseReader->recentSubmissions(300))->to->equal(3);
        });
    });
});
