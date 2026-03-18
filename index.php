<?php

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/Security.php';

// A responsabilidade de manter a sessão viva agora centralizou.
Security::initSession();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri === '' || $requestUri === false) {
    $requestUri = '/';
}

switch ($requestUri) {
    case '/':
        // Agora, bloquear estranhos requer apenas um comando:
        Security::requireAuth();

        require_once __DIR__ . '/app/database.php';
        require_once __DIR__ . '/app/views/home.php';
        break;

    case '/login':
        // A lógica de tentativas ficou simplificada:
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/app/database.php';
            
            $usuario = trim($_POST['usuario'] ?? '');
            $senhaDigitada = $_POST['senha'] ?? '';

            if (empty($usuario) || empty($senhaDigitada)) {
                $erro = "Por favor, preencha todos os campos.";
            } else {
                
                // Entrega a responsabilidade pesada para a classe Security:
                $sucesso = Security::attemptLogin($pdo, $usuario, $senhaDigitada);
                
                if ($sucesso) {
                    header("Location: /");
                    exit;
                } else {
                    $erro = "Usuário ou senha incorretos.";
                }
            }
        }
        
        require_once __DIR__ . '/app/views/login.php';
        break;

    case '/logout':
        // Toda a limpeza de memória, cookies e sessão ficou lá dentro:
        Security::logout();
        break;

    case '/ping':
        echo "pong";
        break;

    default:
        http_response_code(404);
        echo "<h1>Erro 404</h1><p>A página que você está procurando não existe neste servidor.</p>";
        break;
}
