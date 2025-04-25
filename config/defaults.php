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

// Error handler
$settings['error'] = [
    // Should be set to false for the production environment
    'display_error_details' => false,
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

return $settings;
