<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$arquivo = 'produtos.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $quantidade = (int) ($_POST['quantidade'] ?? 0);
    $refugadas = (int) ($_POST['refugadas'] ?? 0);
    $tempo = (float) ($_POST['tempo'] ?? 0);
    $data = date('Y-m-d'); // Data automática

    if ($nome !== '' && $quantidade >= 0 && $refugadas >= 0 && $tempo >= 0) {
        $produto = [
            'nome' => $nome,
            'quantidade' => $quantidade,
            'refugadas' => $refugadas,
            'tempo' => $tempo,
            'data' => $data
        ];

        if (file_exists($arquivo)) {
            $dados = json_decode(file_get_contents($arquivo), true);
        } else {
            $dados = [];
        }

        $dados[] = $produto;
        file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header("Location: inicial.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
     <?php include 'menu.php'?>
<head>
    <meta charset="UTF-8">
    <title>📦 Cadastro de Produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="pt-br">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            color: white;
        }
        label, input {
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">📦 Cadastro de Produto</h2>

    <form method="post" action="cadastro.php" class="card p-4 shadow bg-dark text-white">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" class="form-control bg-dark text-white border-light" required>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade Produzida:</label>
            <input type="number" id="quantidade" name="quantidade" class="form-control bg-dark text-white border-light" required>
        </div>

        <div class="mb-3">
            <label for="refugadas" class="form-label">Quantidade Refugada:</label>
            <input type="number" id="refugadas" name="refugadas" class="form-control bg-dark text-white border-light" required>
        </div>

        <div class="mb-3">
            <label for="tempo" class="form-label">Tempo de Produção (em minutos):</label>
            <input type="number" step="0.1" id="tempo" name="tempo" class="form-control bg-dark text-white border-light" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar Produto</button>
        <a href="inicial.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>