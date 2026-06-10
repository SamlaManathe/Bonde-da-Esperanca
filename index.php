<?php
include "config/db.php";

$sql = "
    SELECT *
    FROM saidas
    WHERE data_saida >= CURDATE()
    ORDER BY data_saida ASC
    LIMIT 1
";

$result = $conexao->query($sql);
$saida = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bonde da Esperança</title>
  <link rel="stylesheet" href="frontend/css/style.css">
</head>

<body class="home-page">

  <!-- HEADER AJUSTADO -->
  <header class="topo" style="justify-content: space-between; padding-right: 30px;">

    <div class="logo">Bonde da Esperança</div>

    <nav style="margin-left: auto;">
      <a href="index.php">Início</a>
      <a href="frontend/pages/sobre.php">Sobre</a>
      <a href="frontend/pages/login.php" class="adm">Admin</a>
    </nav>

  </header>

  <main class="home-container">

    <!-- LADO ESQUERDO -->
    <section class="lado-esquerdo">

      <h1>Participe dessa iniciativa</h1>

      <p class="descricao-ong" id="sobre">
        O Bonde da Esperança é uma iniciativa social liderada por um professor de jiu-jitsu que, junto com voluntários, realiza a distribuição de refeições para moradores de rua sempre que possível.
      </p>

    </section>

    <!-- LADO DIREITO -->
    <section class="lado-direito">

      <!-- CARD DA SAÍDA (AGORA IGUAL AO PIX) -->
      <?php if ($saida): ?>

        <div class="card-apoio">

          <h2>Próxima saída</h2>

          <p>
            <strong>Data da saída:</strong><br>
            <?php echo date('d/m/Y', strtotime($saida['data_saida'])); ?>
          </p>

          <p>
            <strong>Prazo de inscrição:</strong><br>
            <?php echo date('d/m/Y H:i', strtotime($saida['prazo_limite_inscricao'])); ?>
          </p>

          <p>
            <strong>Status:</strong><br>
            <?php echo ucfirst($saida['status']); ?>
          </p>

          <button class="btn-texto verde" onclick="abrirModal(<?php echo $saida['id']; ?>)">
            Quero participar
          </button>

        </div>

      <?php else: ?>

        <div class="card-apoio">
          <h2>Nenhuma saída disponível</h2>
          <p>Em breve novas ações serão cadastradas.</p>
        </div>

      <?php endif; ?>

      <!-- PIX -->
      <div class="card-apoio">

        <h2>Apoie o projeto</h2>

        <p>
          Você pode ajudar com qualquer valor através da chave PIX abaixo:
        </p>

        <button class="btn-texto verde">
          chavepix@projetovoluntarios.com.br
        </button>

      </div>

    </section>

  </main>

  <!-- MODAL -->
  <div id="modalInscricao" class="modal-overlay">

    <div class="modal-container">

      <span onclick="fecharModal()" class="modal-fechar">&times;</span>

      <h2>Inscrição de Voluntário</h2>

      <form method="post" action="backend/core/voluntarios/criarVoluntarios.php">

        <input type="hidden" name="saida_id" id="saida_id">
        <input type="hidden" name="origem" value="home">

        <div class="modal-grupo">
          <label>Nome completo</label>
          <input type="text" name="nome" required>
        </div>

        <div class="modal-grupo">
          <label>Telefone</label>
          <input type="text" name="telefone" required>
        </div>

        <button type="submit" class="btn-texto verde" style="width:100%;">
          Confirmar inscrição
        </button>

      </form>

    </div>

  </div>

  <!-- FOOTER -->
  <footer>Todos os direitos reservados</footer>

  <!-- JS -->
  <script>
    function abrirModal(id) {
        document.getElementById("saida_id").value = id;
        document.getElementById("modalInscricao").style.display = "block";
    }

    function fecharModal() {
        document.getElementById("modalInscricao").style.display = "none";
    }

    window.onclick = function(event) {
        let modal = document.getElementById("modalInscricao");
        if (event.target == modal) {
            fecharModal();
        }
    }
  </script>

</body>
</html>