<?php

declare(strict_types=1);

namespace App\Config;

final class DatabaseConfig
{
    public function __construct(
        public readonly string $host,
        public readonly string $port,
        public readonly string $dbname,
        public readonly string $user,
        public readonly string $password,
    ) {
    }

    public static function fromEnvironment(): self
    {
        $databaseUrl = \getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? null);

        if (\is_string($databaseUrl) && $databaseUrl !== '') {
            $parsed = \parse_url($databaseUrl);
            if (\is_array($parsed) && isset($parsed['host'], $parsed['user'])) {
                return new self(
                    host: (string) $parsed['host'],
                    port: isset($parsed['port']) ? (string) $parsed['port'] : '5432',
                    dbname: \ltrim((string) ($parsed['path'] ?? 'gori'), '/'),
                    user: (string) $parsed['user'],
                    password: (string) ($parsed['pass'] ?? ''),
                );
            }
        }

        return new self(
            host: 'localhost',
            port: '5432',
            dbname: 'gori',
            user: 'postgres',
            password: 'password',
        );
    }

    public function toDsn(): string
    {
        return \sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $this->host,
            $this->port,
            $this->dbname
        );
    }
}
