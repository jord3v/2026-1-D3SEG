<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão DB</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.2rem;
        }
        h1 {
            margin-top: 0;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Status do Sistema</h1>
        
        <div class="my-4">
            <h4 class="fw-normal">Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Visitante'); ?></strong></h4>
            <span class="badge bg-primary">Perfil: <?php echo htmlspecialchars($_SESSION['perfil'] ?? 'Nenhum'); ?></span>
        </div>

        <?php if (isset($pdo)): ?>
            <p class="success">Conexão com o banco de dados '<?php echo htmlspecialchars($_ENV['DB_NAME']); ?>' estabelecida com sucesso!</p>
        <?php else: ?>
            <p class="error">Falha ao conectar com o banco de dados.</p>
        <?php endif; ?>

        <div class="mt-4">
            <a href="/logout" class="btn btn-outline-danger">Sair do Sistema</a>
        </div>
    </div>
</body>
</html>
