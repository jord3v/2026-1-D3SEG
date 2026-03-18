<?php

declare(strict_types=1);


class Security
{
    /**
     * Inicia e gerencia a sessão, garantindo que seja chamada apenas uma vez.
     * Implementa Cookie de Segurança e Expiração de Inatividade.
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Requisitos de mitigação de vulnerabilidades (SameSite Strict, HTTPOnly, etc)
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }

        // Requisito: Expirar sessão por inatividade (Trava de 30 minutos = 1800 segs)
        $timeout = 1800;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            session_unset();
            session_destroy();
            header("Location: /login");
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    /**
     * Gera um token único anti-CSRF e o armazena na sessão
     * @return string O token CSRF seguro
     */
    public static function generateCsrfToken(): string
    {
        self::initSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica se o token recebido no POST é idêntico ao armazenado na Sessão.
     * @param string $token Recebido do formulário
     * @return bool True se confere, False se for um ataque.
     */
    public static function verifyCsrfToken(string $token): bool
    {
        self::initSession();
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Verifica se o usuário está logado. Se não estiver, redireciona para a tela de login.
     */
    public static function requireAuth(): void
    {
        self::initSession();

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login");
            exit;
        }
    }

    /**
     * Verifica se o usuário está logado E se ele é ADMINISTRADOR.
     * Caso contrário, exibe Acesso Negado (HTTP 403).
     */
    public static function requireAdmin(): void
    {
        // Garante ao menos que está logado
        self::requireAuth();

        // Checa a regra de negócio do Perfil
        if ($_SESSION['perfil'] !== 'admin') {
            http_response_code(403);
            die("<h1>403 Forbidden</h1><p>Acesso negado. Apenas administradores podem visualizar esta página.</p>");
        }
    }

    /**
     * Verifica se o usuário está logado E se ele NÃO é um administrador.
     * Caso contrário (se for admin ou visitante), exibe Acesso Negado (HTTP 403).
     */
    public static function requireComum(): void
    {
        self::requireAuth();

        if ($_SESSION['perfil'] === 'admin') {
            http_response_code(403);
            die("<h1>403 Forbidden</h1><p>Acesso negado. Esta página é restrita a usuários comuns.</p>");
        }
    }

    /**
     * Avalia a sessão atual e redireciona o usuário para a página do seu perfil.
     * Deve ser chamada quando o usuário não deveria ver a tela de login.
     */
    public static function redirectByRole(): void
    {
        self::initSession();
        if (isset($_SESSION['usuario_id'])) {
            if ($_SESSION['perfil'] === 'admin') {
                header("Location: /administradores");
            } else {
                header("Location: /usuarios");
            }
            exit;
        }
    }

    /**
     * Destrói inteiramente a sessão do usuário e redireciona para a tela de login.
     */
    public static function logout(): void
    {
        self::initSession();
        
        session_unset();
        session_destroy();
        
        header("Location: /login");
        exit;
    }

    /**
     * Tenta efetuar o login validando usuário e senha no banco de dados.
     * Retorna true em sucesso (e grava a sessão) ou false em falha.
     */
    public static function attemptLogin(PDO $pdo, string $usuario, string $senhaDigitada): bool
    {
        self::initSession();

        $stmt = $pdo->prepare("SELECT id, usuario, senha, perfil FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // password_verify() checa a senha crua com o Hash (sabe lidar sozinho com Bcrypt ou Argon2id)
        if ($user && password_verify($senhaDigitada, $user['senha'])) {
            
            // Requisito: Regenerar o ID da Sessão previne ataques de Fixação de Sessão.
            session_regenerate_id(true);

            // Sucesso! Popula a sessão "oficial" do sistema com os dados necessários.
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['usuario'];
            $_SESSION['perfil'] = $user['perfil'];
            
            return true;
        }

        return false;
    }
}
