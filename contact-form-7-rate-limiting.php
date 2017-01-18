<?php

/*
Plugin Name: Contact Form 7 Rate Limiting
Description: Adds IP-based rate limiting to Contact Form 7
Author: dxw
Author URI: https://www.dxw.com/
*/

$registrar = require __DIR__.'/app/load.php';
$registrar->register();
