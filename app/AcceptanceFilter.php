<?php

namespace Dxw\ContactForm7RateLimiting;

class AcceptanceFilter implements \Dxw\Iguana\Registerable
{
    public function __construct(
        \Dxw\ContactForm7RateLimiting\DatabaseWriter $databaseWriter,
        \Dxw\ContactForm7RateLimiting\DatabaseReader $databaseReader
    ) {
        $this->databaseWriter = $databaseWriter;
        $this->databaseReader = $databaseReader;
    }

    public function register()
    {
        add_filter('wpcf7_spam', [$this, 'filter']);
    }

    public function filter()
    {
        if ($this->databaseReader->recentSubmissions(300) >= 5) {
            return true;
        }

        $this->databaseWriter->recordSubmission();
        return false;
    }
}
