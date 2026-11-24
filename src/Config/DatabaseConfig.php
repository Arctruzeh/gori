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

    /**
     * @param array<string, mixed>|null $env Optional map for deterministic unit testing.
     */
    public static function fromEnvironment(?array $env = null): self
    {
        $databaseUrl = null;
        if ($env !== null) {
            $raw = $env['DATABASE_URL'] ?? null;
            $databaseUrl = \is_string($raw) ? $raw : null;
        } else {
            $raw = \getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? null);
            $databaseUrl = \is_string($raw) ? $raw : null;
        }

        if ($databaseUrl !== null && $databaseUrl !== '') {
            $parsed = \parse_url($databaseUrl);
            if (\is_array($parsed) && isset($parsed['host'], $parsed['user'])) {
                $dbname = \ltrim((string) ($parsed['path'] ?? ''), '/');

                return new self(
                    host: (string) $parsed['host'],
                    port: isset($parsed['port']) ? (string) $parsed['port'] : '5432',
                    dbname: $dbname !== '' ? $dbname : 'gori',
                    user: \rawurldecode((string) $parsed['user']),
                    password: isset($parsed['pass']) ? \rawurldecode((string) $parsed['pass']) : '',
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
