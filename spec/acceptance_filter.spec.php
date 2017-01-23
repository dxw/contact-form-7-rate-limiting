<?php

describe(\Dxw\ContactForm7RateLimiting\AcceptanceFilter::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->databaseWriter = \Mockery::mock(\Dxw\ContactForm7RateLimiting\DatabaseWriter::class, function ($mock) {
        });

        $this->databaseReader = \Mockery::mock(\Dxw\ContactForm7RateLimiting\DatabaseReader::class, function ($mock) {
        });

        $this->acceptanceFilter = new \Dxw\ContactForm7RateLimiting\AcceptanceFilter(
            $this->databaseWriter,
            $this->databaseReader
        );
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->acceptanceFilter)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds a filter', function () {
            \WP_Mock::expectFilterAdded('wpcf7_spam', [$this->acceptanceFilter, 'filter']);
            $this->acceptanceFilter->register();
        });
    });

    describe('->filter()', function () {
        context('when too many recent submissions', function () {
            beforeEach(function () {
                $this->databaseWriter->shouldReceive('recordSubmission')->never();
                $this->databaseReader->shouldReceive('recentSubmissions')->with(300)->andReturn(5);
            });

            it('does not record submission, and returns true', function () {
                expect($this->acceptanceFilter->filter(false))->to->equal(true);
            });
        });

        context('when NOT too many recent submissions', function () {
            beforeEach(function () {
                $this->databaseReader->shouldReceive('recentSubmissions')->with(300)->andReturn(4);
            });

            context('when filterable value is true', function () {
                it('does not record submission, and returns true', function () {
                    $this->databaseWriter->shouldReceive('recordSubmission')->never();
                    expect($this->acceptanceFilter->filter(true))->to->equal(true);
                });
            });

            context('when filterable value is false', function () {
                it('records submission and returns false', function () {
                    $this->databaseWriter->shouldReceive('recordSubmission')->with()->once();
                    expect($this->acceptanceFilter->filter(false))->to->equal(false);
                });
            });
        });
    });
});
