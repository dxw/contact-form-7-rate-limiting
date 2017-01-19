<?php

describe(\Dxw\ContactForm7RateLimiting\DatabaseSchema::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->abspath = \org\bovigo\vfs\vfsStream::setup()->url();
        $this->wpdb = \Mockery::mock(\wpdb::class);
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
        it('registers activation hook', function () {
            \WP_Mock::wpFunction('register_activation_hook', [
                'args' => [dirname(__DIR__).'/contact-form-7-rate-limiting.php', [$this->databaseSchema, 'activationHook']],
                'times' => 1,
            ]);

            $this->databaseSchema->register();
        });
    });

    describe('->activationHook()', function () {
        it('includes upgrade.php and calls dbDelta', function () {
            mkdir($this->abspath.'/wp-admin');
            mkdir($this->abspath.'/wp-admin/includes');
            file_put_contents($this->abspath.'/wp-admin/includes/upgrade.php', '<?php $GLOBALS["has been included"] = true;');
            $GLOBALS['has been included'] = false;

            \WP_Mock::wpFunction('dbDelta', [
                'times' => 1,
            ]);

            $this->databaseSchema->activationHook();
            expect($GLOBALS['has been included'])->to->be->true();
        });
    });
});
