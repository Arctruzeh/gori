<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\DatabaseConfig;
use App\Database\Connection;
use App\Database\SchemaInitializer;

try {
    $pdo = Connection::create(DatabaseConfig::fromEnvironment());
    $initializer = new SchemaInitializer($pdo, __DIR__ . '/schema.sql');
    $initializer->initialize();
    echo "Database schema initialized successfully.\n";
} catch (\Throwable $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
    exit(1);
}
