# D3SEG

Aplicação web em PHP com autenticação e segurança.

## Instalação

1. Instale as dependências:
```bash
composer install
```

2. Configure o arquivo `.env` com suas credenciais de banco de dados

3. Crie as tabelas:
```bash
php scripts/create_tables.php
```

## Uso

Inicie o servidor:
```bash
php -S localhost:8000
```

Acesse `http://localhost:8000` e faça login.

## Estrutura

- `app/` - Código da aplicação
- `scripts/` - Scripts de inicialização
- `index.php` - Roteador principal
- `.env` - Configurações de ambiente