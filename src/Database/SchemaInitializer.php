<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use RuntimeException;

final class SchemaInitializer
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $schemaPath,
    ) {
    }

    public function initialize(): void
    {
        if (!\is_file($this->schemaPath)) {
            throw new RuntimeException("Schema file not found: {$this->schemaPath}");
        }

        $sql = \file_get_contents($this->schemaPath);
        if ($sql === false) {
            throw new RuntimeException("Unable to read schema file: {$this->schemaPath}");
        }

        $this->pdo->exec($sql);
    }
}
