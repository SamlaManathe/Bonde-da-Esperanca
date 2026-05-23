<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard ADM</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <aside class="menu">
    <h3>Bonde da Esperança</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="semanas.php">Semanas</a>
    <a href="voluntarios.php">Voluntários</a>
  </aside>

  <main class="conteudo">
    <div class="barra-topo">
      <h2>Bem vindo, Adm!</h2>
    </div>

    <section class="dashboard-box">
      <p>GRÁFICO QUANTIDADE DE<br>PESSOAS VOLUNTÁRIAS</p>
    </section>
  </main>

  <footer>Todos os direitos reservados</footer>

</body>
</html>

<?php
  include "../../config/db.php";

  $sql = "SELECT COUNT(id) AS Quantidade FROM voluntarios";
  $resultado = $conexao->query($sql);

  if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $quantidadeVoluntarios = $dados['Quantidade'];

    echo "<script>console.log('" . "Quantidade de voluntarios buscados com sucesso" . "')</script>";
    echo "<script>console.log('" . "Total de voluntários: " . $quantidadeVoluntarios . "')</script>";
  } else {
    echo "<script>console.log('" . "Erro ao contar a quantidade de voluntarios" . $conexao->error . "')</script>";
  }

  //echo "<p>" . $sql . "</p>";
  

?>