<?php

// Application default settings

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Stockholm');

$settings = [];

$settings["db"] = [
    "db_name" => "",
    "host" => "localhost",
    "port" => 3306,
    "user" => "root",
    "password" => "root"
];

$settings['error'] = [
    // Should be set to false in production
    'display_error_details' => true,
    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,
    // Display error details in error log
    'log_error_details' => true,
];

// Logger settings
$settings['logger'] = [
    // Log file location
    'path' => __DIR__ . '/../logs',
    // Default log level
    'level' => \Psr\Log\LogLevel::DEBUG,
];

// Comands
$settings['commands'] = [
    \App\Console\ExampleCommand::class,
    // Add more here...
];

$settings['cors'] = [
    'origin' => ['*'],
    'headers.allow' => ['Content-Type', 'Authorization'],
    'credentials' => true
];

return $settings;
