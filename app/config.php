<?php

declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost');