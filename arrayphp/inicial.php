<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Lê os dados do JSON
$produtos = json_decode(file_get_contents("produtos.json"), true);

// Prepara os dados para os gráficos
$nomes = [];
$produzidas = [];
$refugadas = [];

foreach ($produtos as $produto) {
    $nomes[] = $produto["nome"];
    $produzidas[] = $produto["quantidade"];
    $refugadas[] = $produto["refugadas"];
}



$arquivo = 'produtos.json';
$produtos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

function taxaProducao($quantidade, $tempo) {
    return $tempo > 0 ? round($quantidade / $tempo, 2) : 0;
}

function taxaRefugo($refugadas, $quantidade) {
    return $quantidade > 0 ? round(($refugadas / $quantidade) * 100, 2) : 0;
}
// Ordenação
$ordenar_por = $_GET['ordenar_por'] ?? 'data';
$ordem = $_GET['ordem'] ?? 'asc';

$produtos_filtrados = ['graficoLinhas'];
foreach ($produtos as $produto) {
    if (!isset($produto['data'])) continue;
    $data_produto = $produto['data'];
    if ((empty($data_inicial) || $data_produto >= $data_inicial) && (empty($data_final) || $data_produto <= $data_final)) {
        $produtos_filtrados[] = $produto;
    }
}

// Ordenar
usort($produtos_filtrados, function ($a, $b) use ($ordenar_por, $ordem) {
    $valA = $a[$ordenar_por] ?? 'Produzidas';
    $valB = $b[$ordenar_por] ?? 'Refugadas';
   
});
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="pt-br">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
       body {
      background-color: #0e0e0e;
      color: white;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    h1 {
      color:rgb(250, 250, 250);
      text-align: center;
      margin-bottom: 40px;
      position: relative; 
    }
    .chart-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      max-width: 500px;
      margin: auto;
      padding: 70px 0;
    }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <h1 class="navbar-brand">📦 Sistema de Produtos</h1>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuNavbar">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="cadastro.php">Cadastrar Produto</a></li>
        <li class="nav-item"><a class="nav-link" href="listagem.php">Listagem</a></li>
        <li class="nav-item"><a class="nav-link" href="calculos.php">Cálculos</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="sair.php">Sair</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4 text-center">
    <h2>Bem-vindo, <span class="text-primary"><?php echo htmlspecialchars($usuario); ?></span>!</h2>
</div>

<h1>📊 Gráficos de Produção e Refugo</h1>
<div class="chart-container">
    <div class="chart-box"><canvas id="graficoBarra"></canvas></div>
    <div class="chart-box"><canvas id="graficoPizza"></canvas></div>

    </div><form class="row g-3 mb-3" method="GET">
    <div class="col-md-1">
        <label class="form-label">Ordenar por</label>
        <select name="ordenar_por" class="form-select">
            <option value="Produzidas" <?= $ordenar_por === 'Produzidas' ? 'selected' : '' ?>>Produzidas</option>
            <option value="Refugadas" <?= $ordenar_por === 'Refugadas' ? 'selected' : '' ?>>Refugadas</option>
        </select>
    </div>
    <div class="col-md-1">
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
    <div class="chart-box"><canvas id="graficoLinha"></canvas></div>
</div>

<script>
    const nomes = <?php echo json_encode($nomes); ?>;
    const produzidas = <?php echo json_encode($produzidas); ?>;
    const refugadas = <?php echo json_encode($refugadas); ?>;

    const options = {
        responsive: true,
        plugins: {
            legend: { labels: { color: 'white' } },
            title: { display: false }
        },
        scales: {
            x: { ticks: { color: 'white' } },
            y: { ticks: { color: 'white' } }
        }
    };

    new Chart(document.getElementById("graficoBarra"), {
        type: "bar",
        data: {
            labels: nomes,
            datasets: [
                {
                    label: "Produzidas",
                    data: produzidas,
                    backgroundColor: "#00ffcc"
                },
                {
                    label: "Refugadas",
                    data: refugadas,
                    backgroundColor: "#ff4d6d"
                }
            ]
        },
        options
    });

    new Chart(document.getElementById("graficoPizza"), {
        type: "pie",
        data: {
            labels: nomes.map((n, i) => `${n} (Refugo)`),
            datasets: [{
                label: "Refugo Total",
                data: refugadas,
                backgroundColor: nomes.map(() => `hsl(${Math.random() * 360}, 100%, 50%)`)
            }]
        },
        options
    });

    new Chart(document.getElementById("graficoLinha"), {
        type: "line",
        data: {
            labels: nomes,
            datasets: [
                {
                    label: "Produzidas",
                    data: produzidas,
                    borderColor: "#00ffcc",
                    backgroundColor: "#00ffcc33",
                    fill: true,
                    tension: 0.4
                },
                {
                    label: "Refugadas",
                    data: refugadas,
                    borderColor: "#ff4d6d",
                    backgroundColor: "#ff4d6d33",
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options
    });
</script>

</body>
</html>