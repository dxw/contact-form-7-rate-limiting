<?php

describe(\Dxw\ContactForm7RateLimiting\IP::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    describe('->getSource()', function () {
        context('with an IPv4 address', function () {
            beforeEach(function () {
                $this->server = new \Dxw\Iguana\Value\Server([
                    'REMOTE_ADDR' => '127.0.0.1',
                ]);
                $this->ip = new \Dxw\ContactForm7RateLimiting\IP($this->server);
            });

            it('returns the full address', function () {
                expect($this->ip->getSource())->to->equal('127.0.0.1');
            });
        });

        context('with an IPv6 address', function () {
            beforeEach(function () {
                $this->server = new \Dxw\Iguana\Value\Server([
                    'REMOTE_ADDR' => '2001:db8::123',
                ]);
                $this->ip = new \Dxw\ContactForm7RateLimiting\IP($this->server);
            });

            it('returns the full address', function () {
                expect($this->ip->getSource())->to->equal('2001:db8::123');
            });

            xit('returns a /64 subnet', function () {
                //TODO: IPv6 support
                //TODO: should we be returning a /64 subnet or a bigger one?
                expect($this->ip->getSource())->to->equal('2001:db8::/64');
            });
        });
    });
});
