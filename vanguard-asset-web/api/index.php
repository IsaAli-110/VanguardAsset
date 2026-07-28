<?php

// Define bootstrap cache path programmatically before Laravel is loaded
putenv('APP_BOOTSTRAP_CACHE_PATH=/tmp/bootstrap');
$_ENV['APP_BOOTSTRAP_CACHE_PATH'] = '/tmp/bootstrap';
$_SERVER['APP_BOOTSTRAP_CACHE_PATH'] = '/tmp/bootstrap';

// Explicitly set writable paths for all Laravel cache manifests
$cacheFiles = [
    'APP_SERVICES_CACHE' => '/tmp/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/bootstrap/cache/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE' => '/tmp/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/bootstrap/cache/events.php'
];

foreach ($cacheFiles as $key => $path) {
    putenv("{$key}={$path}");
    $_ENV[$key] = $path;
    $_SERVER[$key] = $path;
}

// Create required directories in /tmp for write operations on serverless read-only environment
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Forward request to Laravel public/index.php
require __DIR__ . '/../public/index.php';
