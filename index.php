<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;

$kernel = Kernel::boot();
$response = $kernel->handle();
$response->send();
