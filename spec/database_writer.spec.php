<?php

describe(\Dxw\ContactForm7RateLimiting\DatabaseWriter::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->time = \Mockery::mock(\Dxw\ContactForm7RateLimiting\Time::class, function ($mock) {
            $mock->shouldReceive('now')->andReturn('2000-01-01T00:00:00');
        });
        $this->ip = \Mockery::mock(\Dxw\ContactForm7RateLimiting\IP::class, function ($mock) {
            $mock->shouldReceive('getSource')->andReturn('2001:db8::/64');
        });
        $this->wpdb = \Mockery::mock(\wpdb::class, function ($mock) {
            $mock->prefix = 'wp_';
        });
        $this->databaseWriter = new \Dxw\ContactForm7RateLimiting\DatabaseWriter($this->time, $this->ip, $this->wpdb);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    describe('->recordSubmission()', function () {
        it('records a submission in the database', function () {
            $this->wpdb->shouldReceive('insert')
            ->once()
            ->with('wp_cf7_rate_limiting', ['timestamp' => '2000-01-01T00:00:00', 'source' => '2001:db8::/64']);
            $this->databaseWriter->recordSubmission();
        });
    });
});
