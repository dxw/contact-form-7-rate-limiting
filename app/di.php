<?php

$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\DatabaseSchema(ABSPATH, $GLOBALS['wpdb']));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\IP(
    $registrar->getInstance(\Dxw\Iguana\Value\Server::class)
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\Time());
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\DatabaseWriter(
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\Time::class),
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\IP::class),
    $registrar->getInstance($GLOBALS['wpdb'])
));
