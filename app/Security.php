<?php

class Security
{
    /**
     * Inicia e gerencia a sessão, garantindo que seja chamada apenas uma vez.
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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

        // password_verify() checa a senha crua com o Hash do Bcrypt originário do BD
        if ($user && password_verify($senhaDigitada, $user['senha'])) {
            
            // Sucesso! Popula a sessão "oficial" do sistema com os dados necessários.
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['usuario'];
            $_SESSION['perfil'] = $user['perfil'];
            
            return true;
        }

        return false;
    }
}
