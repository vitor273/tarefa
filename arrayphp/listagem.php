<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$arquivo = 'produtos.json';
$produtos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

function taxaProducao($quantidade, $tempo) {
    return $tempo > 0 ? round($quantidade / $tempo, 2) : 0;
}

function taxaRefugo($refugadas, $quantidade) {
    return $quantidade > 0 ? round(($refugadas / $quantidade) * 100, 2) : 0;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>📦 Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:rgb(0, 0, 0)">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>📦 Lista de Produtos</h2>
        <a href="inicial.php" class="btn btn-secondary">← Voltar ao Menu</a>
    </div>
    <style>
        body{
            color: white;
        }
        </style>

    <?php if (empty($produtos)): ?>
        <div class="alert alert-warning">Nenhum produto cadastrado ainda.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th>Produzidas</th>
                    <th>Refugadas</th>
                    <th>Tempo (min)</th>
                    <th>Taxa de Produção</th>
                    <th>Taxa de Refugo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $index => $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td><?= $p['quantidade'] ?></td>
                        <td><?= $p['refugadas'] ?></td>
                        <td><?= $p['tempo'] ?></td>
                        <td><?= taxaProducao($p['quantidade'], $p['tempo']) ?> un/min</td>
                        <td><?= taxaRefugo($p['refugadas'], $p['quantidade']) ?>%</td>
                        <td>
                            <a href="editar.php?id=<?= $index ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <a href="remover.php?id=<?= $index ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?');">🗑️ Remover</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>