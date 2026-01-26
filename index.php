<?php

/**
 * Strictly OOP Entry Point
 */

require_once __DIR__ . '/vendor/autoload.php';

// Simple Autoloader for App namespace if composer isn't used for it
// (Though composer.json exists, we'll keep the custom one if needed, 
// but it's better to use composer's)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Kernel;

$kernel = new Kernel();
$response = $kernel->handle();
$response->send();