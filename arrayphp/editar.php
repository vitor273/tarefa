<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$arquivo = 'produtos.json';

if (!isset($_GET['id'])) {
    header("Location: listagem.php");
    exit;
}

$id = (int)$_GET['id'];

$dados = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

if (!isset($dados[$id])) {
    echo "Produto não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados[$id] = [
        'nome' => $_POST['nome'],
        'quantidade' => (int)$_POST['quantidade'],
        'refugadas' => (int)$_POST['refugadas'],
        'tempo' => (int)$_POST['tempo']
    ];
    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
    header("Location: listagem.php");
    exit;
}

$produto = $dados[$id];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        body {
        
        background-color:rgb(0, 0, 0);
        color:white;
    }
        </style>
<div class="container mt-5">
    <h2 class="text-center mb-4">✏️ Editar Produto</h2>
    <br>
    <form method="post" action="cadastro.php" class="card p-4 shadow">
        <div class="mb-3">
            <label>Nome do Produto:</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Quantidade Produzida:</label>
            <input type="number" name="quantidade" class="form-control" value="<?= $produto['quantidade'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Quantidade Refugada:</label>
            <input type="number" name="refugadas" class="form-control" value="<?= $produto['refugadas'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Tempo de Produção (min):</label>
            <input type="number" name="tempo" class="form-control" value="<?= $produto['tempo'] ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="listagem.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>