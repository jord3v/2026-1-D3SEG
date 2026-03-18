<?php

require_once __DIR__ . '/app/config.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri === '' || $requestUri === false) {
    $requestUri = '/';
}

switch ($requestUri) {
    case '/':
        require_once __DIR__ . '/app/database.php';
        require_once __DIR__ . '/app/views/home.php';
        break;

    case '/ping':
        echo "pong";
        break;

    default:
        http_response_code(404);
        echo "<h1>Erro 404</h1><p>A página que você está procurando não existe neste servidor.</p>";
        break;
}
