<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - D3SEG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm w-100" style="max-width: 400px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Bem-vindo</h3>
                    <p class="text-muted">Faça login para acessar o sistema.</p>
                </div>
                
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="POST">
                    
                    <div class="mb-3">
                        <label for="usuario" class="form-label text-secondary fw-semibold">Usuário</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Seu nome de usuário" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="senha" class="form-label text-secondary fw-semibold">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Sua senha" required>
                        </div>
                    </div>

                    <div class="d-grid mb-2">
                        <button class="btn btn-primary" type="submit">
                            Entrar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
