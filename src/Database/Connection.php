<?php

declare(strict_types=1);

namespace App\Database;

use App\Config\DatabaseConfig;
use PDO;

final class Connection
{
    public static function create(DatabaseConfig $config): PDO
    {
        return new PDO($config->toDsn(), $config->user, $config->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
