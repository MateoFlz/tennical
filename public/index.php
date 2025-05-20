<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

if (!file_exists(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

require_once __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require_once __DIR__ . '/../config/bootstrap.php';

$app = require __DIR__ . '/../config/slim.php';

// Middleware CORS directo (debe ir antes de AuthMiddleware)
$app->add(new \App\Infrastructure\API\Middleware\DirectCorsMiddleware());

require __DIR__ . '/../src/Infrastructure/API/Routes/slim_routes.php';
$app->add(new \App\Infrastructure\API\Middleware\AuthMiddleware());

$app->run();
