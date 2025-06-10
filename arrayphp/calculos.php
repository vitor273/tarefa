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

function tempoMedio($quantidade, $horas) {
    return $horas > 0 ? round($quantidade / ($horas * 60), 2) : 0; // converte horas em minutos
}

// Variáveis para médias
$totalProducao = 0;
$totalRefugo = 0;
$totalTempo = 0;
$totalProdutos = count($produtos);
$tempoTotalHoras = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>📊 Cálculos de Produção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:rgb(0, 0, 0);">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>📊 Cálculos de Produção</h2>
        <a href="inicial.php" class="btn btn-secondary">← Voltar ao Menu</a>
    </div>

    <?php if (empty($produtos)): ?>
        <div class="alert alert-warning">Nenhum produto cadastrado para calcular.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th>Produzidas</th>
                    <th>Refugadas</th>
                    <th>Tempo (min)</th>
                    <th>Taxa de Produção (un/min)</th>
                    <th>Taxa de Refugo (%)</th>
                    <th>Tempo Médio (un/min)</th>
                </tr>
            </thead>
            <style>
        body{
            color: white;
        }
        </style>
            <tbody>
                <?php foreach ($produtos as $p): 
                    $tp = taxaProducao($p['quantidade'], $p['tempo']);
                    $tr = taxaRefugo($p['refugadas'], $p['quantidade']);
                    $tmp = tempoMedio($p['quantidade'], $p['tempo'] / 60);

                    $totalProducao += $tp;
                    $totalRefugo += $tr;
                    $totalTempo += $tmp;
                    $tempoTotalHoras += $p['tempo'] / 60;
                ?>
                <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= $p['quantidade'] ?></td>
                    <td><?= $p['refugadas'] ?></td>
                    <td><?= $p['tempo'] ?></td>
                    <td><?= $tp ?></td>
                    <td><?= $tr ?></td>
                    <td><?= $tmp ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="4">Médias</td>
                    <td><?= round($totalProducao / $totalProdutos, 2) ?></td>
                    <td><?= round($totalRefugo / $totalProdutos, 2) ?></td>
                    <td><?= round($totalTempo / $totalProdutos, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
     
</div>
</body>
</html>