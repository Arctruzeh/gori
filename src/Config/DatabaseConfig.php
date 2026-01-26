<?php

namespace App\Config;

class DatabaseConfig
{
    public static function get(): array
    {
        $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? null);

        if ($databaseUrl) {
            $parsed = parse_url($databaseUrl);
            return [
                'host' => $parsed['host'],
                'port' => $parsed['port'] ?? '5432',
                'dbname' => ltrim($parsed['path'], '/'),
                'user' => $parsed['user'],
                'password' => $parsed['pass'] ?? '',
            ];
        }

        return [
            'host' => 'localhost',
            'port' => '5432',
            'dbname' => 'gori',
            'user' => 'postgres',
            'password' => 'password',
        ];
    }
}
