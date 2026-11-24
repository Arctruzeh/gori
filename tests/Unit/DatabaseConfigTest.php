<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Config\DatabaseConfig;
use PHPUnit\Framework\TestCase;

final class DatabaseConfigTest extends TestCase
{
    public function testParsesDatabaseUrlWithPortAndEncodedPassword(): void
    {
        $config = DatabaseConfig::fromEnvironment([
            'DATABASE_URL' => 'postgres://gori_user:p%40ss%2F1@pg.internal:5433/gori_prod',
        ]);

        self::assertSame('pg.internal', $config->host);
        self::assertSame('5433', $config->port);
        self::assertSame('gori_prod', $config->dbname);
        self::assertSame('gori_user', $config->user);
        self::assertSame('p@ss/1', $config->password);
        self::assertSame('pgsql:host=pg.internal;port=5433;dbname=gori_prod', $config->toDsn());
    }

    public function testParsesDatabaseUrlWithoutPortOrPassword(): void
    {
        $config = DatabaseConfig::fromEnvironment([
            'DATABASE_URL' => 'postgres://admin@localhost/',
        ]);

        self::assertSame('localhost', $config->host);
        self::assertSame('5432', $config->port);
        self::assertSame('gori', $config->dbname);
        self::assertSame('admin', $config->user);
        self::assertSame('', $config->password);
    }

    public function testFallsBackToLocalDefaults(): void
    {
        $config = DatabaseConfig::fromEnvironment([]);

        self::assertSame('localhost', $config->host);
        self::assertSame('5432', $config->port);
        self::assertSame('gori', $config->dbname);
        self::assertSame('postgres', $config->user);
        self::assertSame('password', $config->password);
    }
}
