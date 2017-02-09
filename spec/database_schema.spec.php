<?php

describe(\Dxw\ContactForm7RateLimiting\DatabaseSchema::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->abspath = \org\bovigo\vfs\vfsStream::setup()->url();
        $this->wpdb = \Mockery::mock(\wpdb::class, function ($mock) {
            $mock->shouldReceive('get_charset_collate');
        });
        $this->wpdb->prefix = 'wp_';
        $this->databaseSchema = new \Dxw\ContactForm7RateLimiting\DatabaseSchema($this->abspath, $this->wpdb);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->databaseSchema)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('registers init hook', function () {
            \WP_Mock::expectActionAdded('init', [$this->databaseSchema, 'init']);

            $this->databaseSchema->register();
        });
    });

    describe('->init()', function () {
        beforeEach(function () {
            mkdir($this->abspath.'/wp-admin');
            mkdir($this->abspath.'/wp-admin/includes');
            file_put_contents($this->abspath.'/wp-admin/includes/upgrade.php', '<?php $GLOBALS["has been included"] = true;');
            $GLOBALS['has been included'] = false;
        });

        context('when version is unset', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('get_option', [
                    'args' => ['cf7_rate_limiting_version'],
                    'return' => false,
                ]);
            });

            it('includes upgrade.php, calls dbDelta, and sets version to 1', function () {
                \WP_Mock::wpFunction('dbDelta', [
                    'times' => 1,
                ]);

                \WP_Mock::wpFunction('update_option', [
                    'args' => ['cf7_rate_limiting_version', 1],
                    'times' => 1,
                ]);

                $this->databaseSchema->init();
                expect($GLOBALS['has been included'])->to->be->true();
            });
        });

        context('when version is 1', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('get_option', [
                    'args' => ['cf7_rate_limiting_version'],
                    'return' => 1,
                ]);
            });

            it('does nothing', function () {
                \WP_Mock::wpFunction('update_option', [
                    'args' => ['cf7_rate_limiting_version', 1],
                    'times' => 0,
                ]);

                $this->databaseSchema->init();
                expect($GLOBALS['has been included'])->to->be->false();
            });
        });
    });
});
