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

// Filtro por data
$data_inicial = $_GET['data_inicial'] ?? '';
$data_final = $_GET['data_final'] ?? '';

// Ordenação
$ordenar_por = $_GET['ordenar_por'] ?? 'data';
$ordem = $_GET['ordem'] ?? 'asc';

// Paginação
$itens_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;

$produtos_filtrados = [];
foreach ($produtos as $produto) {
    if (!isset($produto['data'])) continue;
    $data_produto = $produto['data'];
    if ((empty($data_inicial) || $data_produto >= $data_inicial) && (empty($data_final) || $data_produto <= $data_final)) {
        $produtos_filtrados[] = $produto;
    }
}

// Ordenar
usort($produtos_filtrados, function ($a, $b) use ($ordenar_por, $ordem) {
    $valA = $a[$ordenar_por] ?? '';
    $valB = $b[$ordenar_por] ?? '';
    return $ordem === 'asc' ? $valA <=> $valB : $valB <=> $valA;
});

// Paginas
$total_itens = count($produtos_filtrados);
$total_paginas = ceil($total_itens / $itens_por_pagina);
$inicio = ($pagina_atual - 1) * $itens_por_pagina;
$produtos_pagina = array_slice($produtos_filtrados, $inicio, $itens_por_pagina);
?><!DOCTYPE html><html lang="pt-br">
<?php include 'menu.php'?>
<head>
    <style>
        .pagination{
            justify-content:center;
            margin-top:20px;

        }
        .pagination a{
            color:white;
            background-color:#121212;
            padding: 8px 12px;
            text-decoration:none;
            margin:0 4px;
            border-radius:5px;
            transition: background-color 0.2, color 0.2;
        }
        .pagination a:hover{
           background-color:#DCDCDC;
           color:black;
        }
        .pagination a.active{
            background-color:#DCDCDC;
            color:black;
            font-weight: Medium;
            border:1px solid #DCDCDC;
        }
       

            
        </style>
    <meta charset="UTF-8">
    <title>📦 Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:black; color:white;">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Lista de Produtos</h2>
    </div><form class="row g-3 mb-4" method="GET">
    <div class="col-md-3">
        <label class="form-label">Data Inicial</label>
        <input type="date" name="data_inicial" class="form-control" value="<?= htmlspecialchars($data_inicial) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Data Final</label>
        <input type="date" name="data_final" class="form-control" value="<?= htmlspecialchars($data_final) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Ordenar por</label>
        <select name="ordenar_por" class="form-select">
            <option value="data" <?= $ordenar_por === 'data' ? 'selected' : '' ?>>Data</option>
            <option value="quantidade" <?= $ordenar_por === 'quantidade' ? 'selected' : '' ?>>Produzidas</option>
            <option value="refugadas" <?= $ordenar_por === 'refugadas' ? 'selected' : '' ?>>Refugadas</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Ordem</label>
        <select name="ordem" class="form-select">
            <option value="asc" <?= $ordem === 'asc' ? 'selected' : '' ?>>Crescente</option>
            <option value="desc" <?= $ordem === 'desc' ? 'selected' : '' ?>>Decrescente</option>
        </select>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<?php if (empty($produtos_filtrados)): ?>
    <div class="alert alert-warning">Nenhum produto encontrado no período selecionado.</div>
<?php else: ?>
    <table class="table table-bordered table-striped text-center align-middle table-dark">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Produzidas</th>
                <th>Refugadas</th>
                <th>Tempo (min)</th>
                <th>Data</th>
                <th>Taxa de Produção</th>
                <th>Taxa de Refugo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos_pagina as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= $p['quantidade'] ?></td>
                    <td><?= $p['refugadas'] ?></td>
                    <td><?= $p['tempo'] ?></td>
                    <td><?= $p['data'] ?></td>
                    <td><?= taxaProducao($p['quantidade'], $p['tempo']) ?> un/min</td>
                    <td><?= taxaRefugo($p['refugadas'], $p['quantidade']) ?>%</td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($pagina_atual > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_atual - 1])) ?>">Anterior</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= $pagina_atual == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_atual + 1])) ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

</div>
</body>
</html>