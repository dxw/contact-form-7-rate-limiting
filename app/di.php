<?php

$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\DatabaseSchema(
    ABSPATH,
    $GLOBALS['wpdb']
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\IP(
    $registrar->getInstance(\Dxw\Iguana\Value\Server::class)
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\Time());
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\DatabaseWriter(
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\Time::class),
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\IP::class),
    $GLOBALS['wpdb']
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\DatabaseReader(
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\Time::class),
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\IP::class),
    $GLOBALS['wpdb']
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\AcceptanceFilter(
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\DatabaseWriter::class),
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\DatabaseReader::class)
));
$registrar->addInstance(new \Dxw\ContactForm7RateLimiting\RecordPurger(
    $GLOBALS['wpdb'],
    $registrar->getInstance(\Dxw\ContactForm7RateLimiting\Time::class)
));
