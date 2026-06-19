<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard ADM</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- BARRA SUPERIOR CORRIGIDA E REFINADA -->
    <header class="topo" style="display:flex !important; justify-content:space-between !important; align-items:center !important; padding: 0 40px 0 260px !important; height: 60px !important; background: #3B5D11 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    
    <div class="logo" style="display: flex; align-items: center; height: 100%; font-size: 20px; font-weight: bold; color: white;">Bonde da Esperança</div>

    <nav style="display: flex !important; gap: 15px !important; align-items: center !important; height: 100% !important;">
      <a href="../../index.php" style="color: white; text-decoration: none; background: rgba(255, 255, 255, 0.15); padding: 8px 16px; border-radius: 6px; font-weight: 500; font-size: 14px; transition: background 0.2s; display: inline-block;">Início</a>
      <a href="logout.php" style="color: white; text-decoration: none; background: #d9534f; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; transition: opacity 0.2s; display: inline-block;">Sair</a>
    </nav>

  </header>


  <aside class="menu">
    <h3>Painel de Controle</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="semanas.php">Saídas</a>
    <a href="voluntarios.php">Voluntários</a>
  </aside>

  <!-- REAJUSTADO: Resetamos o margin-left e usamos flexbox para centralizar os cards na área que sobra do menu -->
  <main class="conteudo" style="margin-left: 220px !important; padding: 40px 20px !important; min-height: calc(100vh - 60px); background: #f4f6f3; display: flex !important; justify-content: center !important;">

    <!-- CENTRALIZAÇÃO GARANTIDA: Ocupa toda a área útil até o limite seguro de 750px -->
    <div style="width: 100%; max-width: 750px; display: flex; flex-direction: column; gap: 30px;">
      
      <h2 style="color: #3B5D11; font-size: 26px; font-weight: bold; margin-bottom: -10px;">Visão Geral</h2>

      <?php
        include "../../config/db.php";

        $sql_total = "SELECT COUNT(id) AS Quantidade FROM voluntarios";
        $resultado_total = $conexao->query($sql_total);

        if ($resultado_total) {
          $dados_total = $resultado_total->fetch_assoc();
          $quantidadeVoluntarios = $dados_total['Quantidade'];

          echo "
          <div style='background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-left: 6px solid #4d7917; display: flex; flex-direction: column; gap: 8px;'>
            <span style='color: #666; font-size: 14px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;'>Total de Voluntários Cadastrados</span>
            <h2 style='font-size: 48px; color: #3B5D11; font-weight: 800; margin: 0; line-height: 1;'>" . $quantidadeVoluntarios . "</h2>
          </div>
          ";
        }

        $sql_eventos = "
          SELECT s.data_saida, COUNT(v.id) AS total
          FROM voluntarios v
          INNER JOIN saidas s ON s.id = v.saida_id
          GROUP BY s.data_saida
          ORDER BY s.data_saida ASC
        ";

        $resultado_eventos = $conexao->query($sql_eventos);

        echo "<div style='background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>";
        echo "<h3 style='margin-bottom: 20px; color: #3B5D11; font-size: 18px; font-weight: bold;'>Voluntários por Saída</h3>";

        if ($resultado_eventos && $resultado_eventos->num_rows > 0) {
          
          echo "<div style='display: flex; flex-direction: column; gap: 0;'>";

          while ($linha = $resultado_eventos->fetch_assoc()) {
            $data = date('d/m/Y', strtotime($linha['data_saida']));

            echo "
            <div style='display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;'>
              <span style='font-size: 16px; color: #333; font-weight: 500;'>📅 Saída de " . $data . "</span>
              <span style='background: #eef7ee; color: #3B5D11; font-weight: bold; padding: 6px 14px; border-radius: 20px; font-size: 14px;'>" . $linha['total'] . " voluntários</span>
            </div>
            ";
          }

          echo "</div>";

        } else {
          echo "<p style='color: #888; font-style: italic; margin: 0;'>Nenhum voluntário por saída encontrado.</p>";
        }

        echo "</div>";
      ?>

    </div>

  </main>

  <footer style="position: fixed; bottom: 0; left: 0; background: #3B5D11; color: rgba(255,255,255,0.7); height: 40px; width: 100%; display: flex; align-items: center; justify-content: center; font-size: 13px; z-index: 10;">
    Todos os direitos reservados &copy; Bonde da Esperança
  </footer>

</body>
</html>
