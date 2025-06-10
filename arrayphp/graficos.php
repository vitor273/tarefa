<?php
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Gráficos de Produção</title>
  <br>
  <a href="inicial.php" class="btn btn-secondary"> ← Voltar ao Menu</a>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

    body {
      background-color: #0e0e0e;
      color: white;
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    h1 {
      color:rgb(250, 250, 250);
      text-align: center;
      margin-bottom: 40px;
    }
    .chart-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      max-width: 500px;
      margin: auto;
    }
    canvas {
     
      padding: 20px;
      border-radius: 15px;
     
    }
  </style>
</head>
<body>
  <h1>📊 Gráficos de Produção e Refugo</h1>
  <div class="chart-container">
    <canvas id="graficoBarra"></canvas>
    <canvas id="graficoPizza"></canvas>
    <canvas id="graficoLinha"></canvas>
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
    
    // Gráfico de barras
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

    // Gráfico de pizza
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

    // Gráfico de linha
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