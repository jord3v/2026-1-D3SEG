<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - D3SEG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2 class="mb-3 text-center">Novo Usuário</h2>

        <div class="mb-4 text-center">
            <a href="/administradores" class="text-decoration-none">Voltar ao Painel</a>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <form action="/cadastro" method="POST">
            <div class="mb-3">
                <label for="usuario" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha Inicial</label>
                <input type="password" class="form-control" id="senha" name="senha" required autocomplete="new-password">
            </div>
            <div class="mb-4">
                <label for="perfil" class="form-label">Perfil</label>
                <select class="form-select" id="perfil" name="perfil">
                    <option value="comum">Usuário Comum</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Gravar no Sistema</button>
        </form>
    </div>
</body>
</html>
