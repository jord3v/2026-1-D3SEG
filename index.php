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
        // A rota raiz não tem mais conteúdo próprio, leva para a página padrão (login)
        header("Location: /login");
        exit;

    case '/login':
        // Se a pessoa já estiver logada, joga ela pra tela certa
        Security::redirectByRole();

        // Lógica de recebimento do formulário
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/app/database.php';
            
            $usuario = trim($_POST['usuario'] ?? '');
            $senhaDigitada = $_POST['senha'] ?? '';

            if (empty($usuario) || empty($senhaDigitada)) {
                $erro = "Por favor, preencha todos os campos.";
            } else {
                $sucesso = Security::attemptLogin($pdo, $usuario, $senhaDigitada);
                
                if ($sucesso) {
                    Security::redirectByRole(); // Redireciona logo após o acerto
                } else {
                    $erro = "Usuário ou senha incorretos.";
                }
            }
        }
        
        require_once __DIR__ . '/app/views/login.php';
        break;

    case '/administradores':
        // Apenas Administradores puros e autenticados acessam!
        Security::requireAdmin();
        require_once __DIR__ . '/app/views/administradores.php';
        break;

    case '/usuarios':
        // Apenas Usuários Comuns (Não-Admins) e autenticados acessam!
        Security::requireComum();
        require_once __DIR__ . '/app/views/usuarios.php';
        break;

    case '/logout':
        Security::logout();
        break;

    case '/cadastro':
        // ===================================
        // PROTEÇÃO FORTE: SÓ ADMIN ENTRA AQUI
        // ===================================
        Security::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/app/database.php';

            $novoUsuario = trim($_POST['usuario'] ?? '');
            $novaSenha   = trim($_POST['senha'] ?? '');
            $novoPerfil  = $_POST['perfil'] ?? 'comum';

            if (empty($novoUsuario) || empty($novaSenha)) {
                $erro = "Por favor, preencha todos os campos.";
            } else {
                // Checa se o usuário já existe
                $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
                $stmtCheck->execute([$novoUsuario]);
                
                if ($stmtCheck->fetch()) {
                    $erro = "Já existe um cadastro com este nome de usuário.";
                } else {
                    // Bcrypt entra em ação
                    $hash = password_hash($novaSenha, PASSWORD_BCRYPT);
                    
                    $stmtInsert = $pdo->prepare("INSERT INTO usuarios (usuario, senha, perfil) VALUES (?, ?, ?)");
                    if ($stmtInsert->execute([$novoUsuario, $hash, $novoPerfil])) {
                        $sucesso = "Usuário '{$novoUsuario}' cadastrado com sucesso!";
                    } else {
                        $erro = "Ocorreu um erro interno ao cadastrar o usuário.";
                    }
                }
            }
        }

        require_once __DIR__ . '/app/views/cadastro.php';
        break;

    case '/ping':
        echo "pong";
        break;

    default:
        http_response_code(404);
        echo "<h1>Erro 404</h1><p>A página que você está procurando não existe neste servidor.</p>";
        break;
}
