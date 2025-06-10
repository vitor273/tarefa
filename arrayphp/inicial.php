<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="pt-br">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f1f1f1;
    }
    .navbar {
        background-color: #343a40;
    }
    .navbar a {
        color: white;
        margin-right: 15px;
        text-decoration: none;
    }
    .navbar a:hover {
        color: #ffc107;
    }
</style>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
    <h1 class="navbar-brand">📦 Sistema de Produtos</h1>
    <div class="ms-auto">
        <a href="inicial.php">🏠 Início</a>
        <a href="cadastro.php">➕ Cadastrar Produto</a>
        <a href="listagem.php">📋 Listagem</a>
        <a href="calculos.php">🧮 Cálculos</a>
        <a href="graficos.php">📊 Graficos</a>
        <a href="sair.php">🚪 Sair</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="text-center">
        <h2>Bem-vindo, <span class="text-primary"><?php echo htmlspecialchars($usuario); ?></span>!</h2>
        <p class="lead">Utilize o menu acima para gerenciar os produtos, visualizar a listagem ou realizar cálculos automáticos.</p>
    </div>
</div>

</body>
</html>