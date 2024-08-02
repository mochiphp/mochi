<?php

// Application default settings

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Berlin');

$settings = [];

// Error handler
$settings['error'] = [
    // Should be set to false for the production environment
    'display_error_details' => false,
];

// Logger settings
$settings['logger'] = [
    // Log file location
    'path' => __DIR__ . '/../../../../var/logs',
    // Default log level
    'level' => Psr\Log\LogLevel::DEBUG,
];

$settings['db'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'database',
    'username' => 'user',
    'password' => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

return $settings;
