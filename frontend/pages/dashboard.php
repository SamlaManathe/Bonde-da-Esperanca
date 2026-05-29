<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard ADM</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- BARRA SUPERIOR ADICIONADA -->
  <header class="topo" style="display:flex; justify-content:space-between; align-items:center; padding-right:30px;">

    <div class="logo">Bonde da Esperança</div>

    <nav>
      <a href="../../index.php">Início</a>
      <a href="../../index.php#sobre">Sobre</a>
      <a href="dashboard.php">Admin</a>
    </nav>

  </header>

  <aside class="menu">
    <h3>Bonde da Esperança</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="semanas.php">Saídas</a>
    <a href="voluntarios.php">Voluntários</a>
  </aside>

  <main class="conteudo">

    <div class="barra-topo">
      <h2>Bem vindo, Adm!</h2>
    </div>

    <section class="dashboard-box" style="flex-direction: column; height:auto; padding:40px; gap:20px;">

      <?php
        include "../../config/db.php";

        // TOTAL GERAL
        $sql_total = "SELECT COUNT(id) AS Quantidade FROM voluntarios";
        $resultado_total = $conexao->query($sql_total);

        if ($resultado_total) {
          $dados_total = $resultado_total->fetch_assoc();
          $quantidadeVoluntarios = $dados_total['Quantidade'];

          echo "<h1 style='color:#8fd18f;'>Total de voluntários</h1>";
          echo "<h2 style='font-size:42px;'>" . $quantidadeVoluntarios . "</h2>";
        }

        // POR DATA DA SAÍDA
        $sql_eventos = "
          SELECT s.data_saida, COUNT(v.id) AS total
          FROM voluntarios v
          INNER JOIN saidas s ON s.id = v.saida_id
          GROUP BY s.data_saida
          ORDER BY s.data_saida ASC
        ";

        $resultado_eventos = $conexao->query($sql_eventos);

        if ($resultado_eventos && $resultado_eventos->num_rows > 0) {

          echo "<h1 style='margin-top:20px; color:#8fd18f;'>Voluntários por saída</h1>";

          while ($linha = $resultado_eventos->fetch_assoc()) {

            $data = date('d/m/Y', strtotime($linha['data_saida']));

            echo "<h2 style='font-size:20px; font-weight:normal; margin:5px 0;'>
                    " . $data . " → " . $linha['total'] . " voluntários
                  </h2>";
          }

        } else {
          echo "<h2>Nenhum voluntário por saída encontrado</h2>";
        }
      ?>

    </section>

  </main>

  <footer>Todos os direitos reservados</footer>

</body>
</html>