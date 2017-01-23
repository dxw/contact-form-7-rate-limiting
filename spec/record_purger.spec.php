<?php

describe(\Dxw\ContactForm7RateLimiting\RecordPurger::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        \phpmock\mockery\PHPMockery::mock(\Dxw\ContactForm7RateLimiting::class, 'time')->andReturn('value of time()');

        $this->time = \Mockery::mock(\Dxw\ContactForm7RateLimiting\Time::class, function ($mock) {
            $mock->shouldReceive('now')->with()->andReturn('TIMESTAMP');
        });
        $this->wpdb = \Mockery::mock(\wpdb::class, function ($mock) {
        });
        $this->recordPurger = new \Dxw\ContactForm7RateLimiting\RecordPurger(
            $this->wpdb,
            $this->time
        );
    });

    afterEach(function () {
        \WP_Mock::tearDown();
        \Mockery::close();
    });

    it('is registerable', function () {
        expect($this->recordPurger)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        beforeEach(function () {
            \WP_Mock::expectActionAdded('ContactForm7RateLimiting_purge_records', [$this->recordPurger, 'purgeRecords']);
        });

        context('when an event is not scheduled', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('wp_next_scheduled', [
                    'args' => ['ContactForm7RateLimiting_purge_records'],
                    'return' => false,
                ]);
            });

            it('calls wp_schedule_event (and registers action)', function () {
                \WP_Mock::wpFunction('wp_schedule_event', [
                    'args' => ['value of time()', 'daily', 'ContactForm7RateLimiting_purge_records'],
                    'times' => 1,
                ]);

                $this->recordPurger->register();
            });
        });

        context('when an event is already scheduled', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('wp_next_scheduled', [
                    'args' => ['ContactForm7RateLimiting_purge_records'],
                    'return' => true,
                ]);
            });

            it('does nothing (and registers action)', function () {
                \WP_Mock::wpFunction('wp_schedule_event', [
                    'times' => 0,
                ]);

                $this->recordPurger->register();
            });
        });
    });

    describe('->purgeRecords()', function () {
        it('purges old records', function () {
            $this->wpdb->prefix = 'wp_';
            $this->wpdb->shouldReceive('query')->with('correct SQL')->once();
            $this->wpdb->shouldReceive('prepare')->with('DELETE FROM wp_cf7_rate_limiting WHERE timestamp < %s', '24 hours ago')->andReturn('correct SQL');
            $this->time->shouldReceive('secondsAgo')->with(24*60*60)->andReturn('24 hours ago');

            $this->recordPurger->purgeRecords();
        });
    });
});
