<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: inicial.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            color: white;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #333;
            border-radius: 15px;
            box-shadow: 0 0 20px #000;
        }
    </style>
</head>
<body>
    <br>
    </br>
    <div class="login-container">
        <h3 class="text-center mb-4">🔐 Login</h3>
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger text-center">Credenciais inválidas</div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" required placeholder="Digite seu email">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha" required placeholder="Digite sua senha">
            </div>
            <div class="d-grid">
                <button class="btn btn-primary" type="submit">Entrar</button>
            </div>
            <body>
            <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
            &nbsp; &nbsp;<a class="navbar-brand">nao tem conta?</a>
        <a href="registro.php">registrar-se</a>
        </body>
        </form>
    </div>
</body>
</html>