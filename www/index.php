<?php

declare(strict_types=1);

use Contributte\Middlewares\Application\IApplication as ApitteApplication;
use Nette\Application\Application as NetteApplication;

require __DIR__ . '/../vendor/autoload.php';

set_time_limit(300);
ini_set('memory_limit', '256M');

$isApi = str_starts_with($_SERVER['REQUEST_URI'], '/api');

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();

if ($isApi) {
    // Apitte application
    $container->getByType(ApitteApplication::class)->run();
} else {
    // Nette application
    $container->getByType(NetteApplication::class)->run();
}


