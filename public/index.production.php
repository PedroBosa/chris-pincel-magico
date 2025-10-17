<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ajustar caminho para InfinityFree (um nível acima do htdocs)
if (file_exists($maintenance = __DIR__.'/../chris-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../chris-app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../chris-app/bootstrap/app.php';

$app->handleRequest(Request::capture());
