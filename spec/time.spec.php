<?php

describe(\Dxw\ContactForm7RateLimiting\Time::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        \phpmock\mockery\PHPMockery::mock(\Dxw\ContactForm7RateLimiting::class, "time")->andReturn(1000);
        $this->time = new \Dxw\ContactForm7RateLimiting\Time();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
        \Mockery::close();
    });

    describe('->now()', function () {
        it('returns the current time', function () {
            \phpmock\mockery\PHPMockery::mock(\Dxw\ContactForm7RateLimiting::class, "gmstrftime")->with('%Y-%m-%dT%H:%M:%S', 1000)->andReturn('2017-01-19T11:27:59');

            expect($this->time->now())->to->equal('2017-01-19T11:27:59');
        });
    });

    describe('->secondsAgo()', function () {
        it('returns the time 100 seconds ago', function () {
            \phpmock\mockery\PHPMockery::mock(\Dxw\ContactForm7RateLimiting::class, "gmstrftime")->with('%Y-%m-%dT%H:%M:%S', 900)->andReturn('2017-01-19T11:27:59');

            expect($this->time->secondsAgo(100))->to->equal('2017-01-19T11:27:59');
        });
    });
});
