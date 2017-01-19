<?php

describe(\Dxw\ContactForm7RateLimiting\Time::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->time = new \Dxw\ContactForm7RateLimiting\Time();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
        \Mockery::close();
    });

    describe('->now()', function () {
        it('returns the current time', function () {
            \phpmock\mockery\PHPMockery::mock(\Dxw\ContactForm7RateLimiting::class, "gmstrftime")->andReturn('2017-01-19T11:27:59');

            expect($this->time->now())->to->equal('2017-01-19T11:27:59');
        });
    });
});
