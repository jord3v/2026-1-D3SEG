<?php

require_once __DIR__ . '/../app/database.php';

try {
    echo "Iniciando insercao do usuario de teste...\n";

    $usuario = 'admin';
    $senhaPlana = '123456';
    $perfil = 'admin';

    $senhaHash = password_hash($senhaPlana, PASSWORD_BCRYPT);
    
    echo "Senha Plana digitada: {$senhaPlana}\n";
    echo "Hash gerado (Bcrypt): {$senhaHash}\n\n";

    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmtCheck->execute([$usuario]);
    if ($stmtCheck->fetchColumn() > 0) {
        die("O usuario '{$usuario}' ja existe no banco!\n");
    }

    $sql = "INSERT INTO usuarios (usuario, senha, perfil) VALUES (:usuario, :senha, :perfil)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':usuario' => $usuario,
        ':senha' => $senhaHash,
        ':perfil' => $perfil
    ]);

    echo "Usuario '{$usuario}' criado com sucesso e senha encriptada!\n";

} catch (PDOException $e) {
    echo "Erro ao inserir usuario: " . $e->getMessage() . "\n";
}
