<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário - D3SEG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Página de Acesso Restrito aos Usuários Comuns</h2>
            <a href="/logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
        
        <p class="lead">Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>!</p>
        <p>Esta é a área restrita exclusiva para Usuários Comuns.</p>
    </div>
</body>
</html>
